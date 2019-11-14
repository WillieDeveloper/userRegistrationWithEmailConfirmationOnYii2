<?php
    class model {
    //Идентификатор БД
    protected $dbLink;
    
    //В конструкторе сразу создаём подключение к БД
    public function __construct () {
        $this->dbLink = $this->dbConnect ();
    }
    
    //Подключаемся к БД либо создаём новую, если не обнаружена
    protected function dbConnect () {
        //Проверка на доступность БД
        if (!mysqli_connect (DB_HOST, DB_USER, DB_PASS) ) {
            return false;
        }
        
        //Получаем идентификатор
        $dbLink = mysqli_connect (DB_HOST, DB_USER, DB_PASS);

        //Если такой БД нет, то создаём
        if(!mysqli_select_db ($dbLink, DB_NAME) ) {
            if ( !mysqli_query ($dbLink, 'CREATE DATABASE ' . DB_NAME) ) {
                return false;
            }
        }

        return $dbLink;
    }

    //Заполняем таблицу городами из файла
    protected function getCitiesFromFile ($dbLink, $citiesFile) {   
        
        //Проверяем наличие файла
        if (!file_exists ($citiesFile) ) {
            return false;
        }
        
        //Получаем его содержимое
        $fileContent = file_get_contents ($citiesFile);

        //Если не удалось, то возвращаем Фолс
        if (!$fileContent) {
            return false;   
        }

        //Разбиваем содержимое и помещаем в массив
        $citiesArray = explode ('\\n', $fileContent);
        
        //Формируем запрос к БД
        $insertCitiesQuery = 'INSERT INTO cities (id_city, name_city) VALUE ';

        for ($i = 0; $i < count ($citiesArray); $i++) {
            $insertCitiesQuery = $insertCitiesQuery.'(NULL, ' . $citiesArray[$i] . '), ';   
            
            if ($i == (count ($citiesArray) - 1) ) {
                $insertCitiesQuery = $insertCitiesQuery. '(NULL, ' . $citiesArray[$i] . ')'; 
            }
        }

        //Проверяем успешность обращения к БД
        if(!mysqli_query ($dbLink, $insertCitiesQuery) ) {
            unset ($insertCitiesQuery);
            return false;
        }

        //Чистим память от здоровенного запроса
        unset ($insertCitiesQuery);
        return true;
    }

    // Проверка таблицы на существование
    protected function showTable ($dbLink, $dbName, $table) {
        if (!mysqli_query ($dbLink, 'SHOW TABLES FROM ' . $dbName . ' LIKE ' . $table) ) {
            return false;
        }
        return true;
    }

    //Получение неассоциативного массива данных из БД
    protected function selectArray ($query) {
        
        //Проверяем идентификатор
        if (!$this->dbLink) {
            return false;
        }

        //Запрашиваем данные
        $result = mysqli_query ($this->dbLink, $query);
        if (!$result) {
            return false;
        }
        
        //Формируем массив 
        $i = 0;
        while ($resultNotice = mysqli_fetch_row ($result) ) {
            for ($j = 0; $j < count ($resultNotice); $j++) {
                $resultArray[$i][$j] = $resultNotice[$j];
            }
            $i++;
        }
        return $resultArray;
    }
    }
?>