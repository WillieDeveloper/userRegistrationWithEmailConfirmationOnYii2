<?php
    class addUserController extends controller {
        function indexAction () {
            //Открываем сессию и кладём в файл введённые данные
            session_start ();
            $_SESSION['name'] = $_POST['user_name'];
            $_SESSION['age'] = $_POST['user_age'];
            //Проверяем ввод на корректность
            if ( (preg_match ('/[0-9_+=-?*&^%$#@!:;"><,.]+/', $_POST['user_name']) || ($_POST['user_name'] == '') ) ) {
                $this->data = 'Вводимое имя должно состоять только из букв латиницы или кирилицы!';
            }
            if (!(preg_match ("|^[\d]{2}$|", $_POST['user_age']) || ($_POST['user_age'] == '100') ) ) {
                $this->data = 'Неверно указан возраст! Можно ввести значение от 10 до 100!';
            }
            
            //Если данные корректны, вызываем функцию модели для добавления данных в БД
            if (!$this->data) {
                $this->data = $this->model->addData ($_POST['user_name'], $_POST['user_age'], $_POST['user_city']);
                session_destroy ();
            }
            //$this->createPage ('view_msg_page_template.html');
            //Отрисовываем сообщение
            $this->generatePageViaTwig ('SysMsg', 'Системное сообщение', 'Msg');
        }
    }
?>