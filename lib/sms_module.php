<?php
//-------------------------------------------------------------------------------------------------------
//Класс для рассылки смс
class Sms
{
    protected $baseurl = "http://api.comtube.ru/scripts/api/sms.php";          //адрес сервиса - для отправки запроса
    protected $username = ""; //"Guest";                                      //логин
    protected $password = ""; //"qwerty";                                      //пароль

    protected $params = array();                                               //массив переданных параметров
    protected $encode_params = "";                                             //преобразованные параметры, готовые к отправке
    protected $result = array();                                               //результаты отправки SMS


    //свойства для отправки конкретного письма
    protected $message = "";
    protected $number =null;
    protected $senderid = null;
    protected $method = "post";
    protected $return_type = "xml";
    protected $action = "send";


    /**
     * Конструктор - определяет пользователя comtube
     *
     * @param строка - логин пользователя
     * @param строка - пароль
     * @return ComtubeSMS - объект, посредством которого осуществляется отправка SMS
     */
    public function __construct($login, $password)
    {
        if ($login && ($login !== '') && (strlen($login) > 0)) $this->username = $login;
        if ($password && ($password !== '') && (strlen($password) > 0)) $this->password = $password;
    }

    /**
     * Метод, отвечающий за формирование и отправку письма тем или иным методом
     *
     * @param строка - сообщение для отправки
     * @param массив/строка - номер/номера получателя. Запись нескольких номеров возможна массивом или через ";". 218784 - тестовый номер
     * @param строка - номер отправителя - не более 11 симоволов. По умолчанию "comtube"
     * @param get/post - принимает 2 возможных значения: get и post. С помощью метода POST возможно отправлять любые объёмы информации(свыше 2000 символов по сравнению с GET), однако, для работы необходима библиотека cURL
     */
    public function send_sms($message, $number, $senderid=null, $method="post")
    {
        //сам текст сообщения
        $this->message = $message;
        //если это строка, сделаем из неё массив из одного элемента
        if(!is_array($number)) $this->number = array($number);
        else $this->number = $number;
        //от кого
        $this->senderid = $senderid;
        //метод отправки
        $this->method = $method;


        //преобразуем номер телефона для отправки
        $this->create_phone();
        //создаём параметры для отправки
        $this->create_params();
        //а теперь отправляем
        $this->go_sending();
        //вызываем пользовательскую функцию
        $this->user_function();

        return $this->result;
    }

    //Преобразуем номер телефона в удобоваримый вид
    protected function create_phone()
    {
        $array = array();

        foreach($this->number as $number){
            $number = preg_replace("/[^0-9]/",'',$number);
            $len = strlen($number);

            if($len == 11) {$number = "7".substr($number,1);}
            elseif($len == 10) {$number = "7".$number;}

            $array[] = $number;
        }
        $this->number = $array;
    }

    /**
     * Создаём каркас - массив, содеражщий все параметры.
     *
     */
    protected function create_params()
    {
        $this->params =array();
        $this->params['action'] = $this->action;
        $this->params['username'] = $this->username;
        $this->params['message'] = $this->message;
        $this->params['number'] = implode(";",$this->number);
        if(!is_null($this->senderid)) $this->params['senderid'] = substr($this->senderid, 0, 11);
        $this->params['type']=$this->return_type;
        //сортируем - это обязательно!
        ksort($this->params);

        //и вызываем метод, который закодируем и соберёт параметры в нужный нам вид
        $this->create_encode_params();
    }

    /**
     * Метод, создающий из массива $this->params данные, готовые для отправки одним из методов
     *
     */
    protected function create_encode_params()
    {
        $this->encode_params="";
        foreach($this->params as $key => $value)
        {
            $this->encode_params .= $key . "=" . urlencode($value) . "&";
        }
        $this->encode_params .= "signature=" . md5($this->encode_params . "&password=".urlencode($this->password));
    }

    /**
     * Запуск рассылки на указанные номера
     *
     * @param массив/строка - номер/номера получателя
     * @param get/post - принимает 2 возможных значения: get и post. С помощью метода POST возможно отправлять
     */
    protected function go_sending()
    {
        $this->result = array_merge($this->result, $this->params);

        //проверяем наличие такого метода
        $method_name = "use_".$this->method;
        if(!method_exists($this, $method_name))
        {
            $this->result['code_error'] = "Указан неверный метод отправки: $method_name. Допустимые методы: post и get.";
            return false;
        }

        //если такой метод есть, рассылаем на указанные номера
        $check_result = $this->check_result($this->$method_name());
        $this->result = array_merge($this->result, $check_result);
        return true;
    }

    /**
     * Отправка сообщения запросом GET
     *
     */
    protected function use_get()
    {
        $result = file_get_contents($this->baseurl."?".$this->encode_params);
        return $result;
    }

    /**
     * Отправка сообщения запросом POST
     *
     */
    protected function use_post()
    {
        $curl = curl_init();
        if(!$curl)
        {
            $this->result['code_error']="Не удалось инициализировать curl";
            return false;
        }

        curl_setopt($curl, CURLOPT_URL, $this->baseurl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->encode_params);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    /**
    * Виртуальный метод, позволяющий навесить какую-либо дополнительную обработку.
    * Например, запись в БД или отправка отчёта об отправке на почту
    * Для использования необходимо создать класс, наследуемый от ComtubeSMS или ComtubeSMS_notXML
    * и реализовать данный метод
    * 
    */
    protected function user_function()
    {}

    /**
     * Обработка результатов работы XML
     *
     */
    protected function check_result($result)
    {
        $return_result_array = array();
        if($result)
        {
            $simple_xml_obj = new SimpleXMLElement($result);
            foreach($simple_xml_obj as $key => $value)
            {
                $return_result_array[$key] = (string)$value;
            }
        }
        return $return_result_array;
    }
}
?>