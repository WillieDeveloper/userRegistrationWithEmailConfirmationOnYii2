<?php
    class addUserModel extends model {
        
        //Добавление введённых данных
        public function addData ($userName, $userAge, $cityID) {
            
            //Получаем id города из таблицы cities для вставки в users
            $answer = 'Пользователь успешно добавлен!';
            $match = mysqli_fetch_assoc (mysqli_query ($this->dbLink, 'SELECT id_city FROM cities 
                WHERE id_city = ' . $cityID) );
            
            //Если в БД совпадений нет...
            if (!$match) {
                $answer = 'Совпадений по городу не найдено!';
                return $answer;
            }
            
            //Попытка добавления пользователя в БД
            if (!mysqli_query ($this->dbLink, 'INSERT INTO users (id_user, name_user, age_user, id_city) 
                VALUE (NULL, \'' . $userName . '\', \'' . $userAge . '\', \'' . $cityID . '\')') ) {
                $answer = 'Не удалось добавить пользователя!';
                return $answer;
            }
            
            return $answer;
        }
    }
?>