<?php
    ini_set('error_reporting', E_ERROR );

    $example = array(
        'Tilda UID' => "",
        'Brand' => "",
        'SKU' => "",
        'Mark' => "",
        'Category' => "",
        'Title' => "",
        'Description' => "",
        'Text' => "",
        'Photo' => "",
        'Price' => "",
        'Quantity' => "",
        'Price Old' => "",
        'Editions' => "",
        'Modifications' => "",
        'External ID' => "",
        'Parent UID' => "",
        'Characteristics:Двигатель' => "",
        'Characteristics:Состояние' => "",
        'Characteristics:Модель автомобиля' => "",
        'Characteristics:Пробег' => "",
        'Characteristics:Тип кузова' => "",
        'Characteristics:Трансмиссия' => "",
        'Characteristics:Руль' => "",
        'Characteristics:Привод' => "",
        'Characteristics:Цвет' => "",
        'Characteristics:Год выпуска' => "",
        'Characteristics:Тип транспорта' => "",
        'Weight' => "",
        'Length' => "",
        'Width' => "",
        'Height' => "",
        'SEO title' => "",
        'SEO descr' => "",
        'SEO keywords' => "",
        'FB title' => "",
        'FB descr' => ""
    );

    function download_page($path){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$path);
        curl_setopt($ch, CURLOPT_FAILONERROR,1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $retValue = curl_exec($ch); 
        $data= curl_getinfo ($ch);	
        curl_close($ch);	
        return $retValue;	
    }

    function create_csv_file( $create_data, $file = null, $col_delimiter = ';', $row_delimiter = "\r\n" ){

        if( ! is_array( $create_data ) ){
            return false;
        }

        if( $file && ! is_dir( dirname( $file ) ) ){
            return false;
        }

        // строка, которая будет записана в csv файл
        $CSV_str = '';

        // перебираем все данные
        foreach( $create_data as $row ){
            $cols = array();

            foreach( $row as $col_val ){
                // строки должны быть в кавычках ""
                // кавычки " внутри строк нужно предварить такой же кавычкой "
                if( $col_val && preg_match('/[",;\r\n]/', $col_val) ){
                    // поправим перенос строки
                    if( $row_delimiter === "\r\n" ){
                        $col_val = str_replace( [ "\r\n", "\r" ], [ '\n', '' ], $col_val );
                    }
                    elseif( $row_delimiter === "\n" ){
                        $col_val = str_replace( [ "\n", "\r\r" ], '\r', $col_val );
                    }

                    $col_val = str_replace( '"', '""', $col_val ); // предваряем "
                    $col_val = '"'. $col_val .'"'; // обрамляем в "
                }

                $cols[] = $col_val; // добавляем колонку в данные
            }
            $CSV_str .= implode( $col_delimiter, $cols ) . $row_delimiter; // добавляем строку в данные
        }

        $CSV_str = rtrim( $CSV_str, $row_delimiter );

        // задаем кодировку windows-1251 для строки
        if( $file ){
            $CSV_str = iconv( "UTF-8", "cp1251",  $CSV_str );

            // создаем csv файл и записываем в него строку
            $done = file_put_contents( $file, $CSV_str );

            return $done ? $CSV_str : false;
        }

        return $CSV_str;

    }

    function parse_csv_file( $file_path, $file_encodings = ['cp1251','UTF-8'], $col_delimiter = '', $row_delimiter = '' ){

        if( ! file_exists( $file_path ) ){
            return false;
        }

        $cont = trim( file_get_contents( $file_path ) );

        $encoded_cont = mb_convert_encoding( $cont, 'UTF-8', mb_detect_encoding( $cont, $file_encodings ) );

        unset( $cont );

        // определим разделитель
        if( ! $row_delimiter ){
            $row_delimiter = "\r\n";
            if( false === strpos($encoded_cont, "\r\n") )
                $row_delimiter = "\n";
        }

        $lines = explode( $row_delimiter, trim($encoded_cont) );
        $lines = array_filter( $lines );
        $lines = array_map( 'trim', $lines );

        // авто-определим разделитель из двух возможных: ';' или ','.
        // для расчета берем не больше 30 строк
        if( ! $col_delimiter ){
            $lines10 = array_slice( $lines, 0, 30 );

            // если в строке нет одного из разделителей, то значит другой точно он...
            foreach( $lines10 as $line ){
                if( ! strpos( $line, ',') ) $col_delimiter = ';';
                if( ! strpos( $line, ';') ) $col_delimiter = ',';

                if( $col_delimiter ) break;
            }

            // если первый способ не дал результатов, то погружаемся в задачу и считаем кол разделителей в каждой строке.
            // где больше одинаковых количеств найденного разделителя, тот и разделитель...
            if( ! $col_delimiter ){
                $delim_counts = array( ';'=>array(), ','=>array() );
                foreach( $lines10 as $line ){
                    $delim_counts[','][] = substr_count( $line, ',' );
                    $delim_counts[';'][] = substr_count( $line, ';' );
                }

                $delim_counts = array_map( 'array_filter', $delim_counts ); // уберем нули

                // кол-во одинаковых значений массива - это потенциальный разделитель
                $delim_counts = array_map( 'array_count_values', $delim_counts );

                $delim_counts = array_map( 'max', $delim_counts ); // берем только макс. значения вхождений

                if( $delim_counts[';'] === $delim_counts[','] )
                    return array('Не удалось определить разделитель колонок.');

                $col_delimiter = array_search( max($delim_counts), $delim_counts );
            }

        }

        $data = [];
        foreach( $lines as $key => $line ){
            $data[] = str_getcsv( $line, $col_delimiter ); // linedata
            unset( $lines[$key] );
        }

        return $data;
    }

    $url = "https://turbodealer.ru/export/218914_yml_tilda.xml";
    $export = __DIR__."/export.xml";

    file_put_contents($export, download_page($url));

    $cars = array(array('Brand', 'SKU', 'Mark', 'Category', 'Title', 'Description', 'Text', 'Photo', 'Price', 'Quantity', 'Price Old', 'Editions', 'Modifications', 'External ID', 'Parent UID', 'Characteristics:Двигатель', 'Characteristics:Состояние', 'Characteristics:Модель автомобиля', 'Characteristics:Пробег', 'Characteristics:Тип кузова', 'Characteristics:Трансмиссия', 'Characteristics:Руль', 'Characteristics:Привод', 'Characteristics:Цвет', 'Characteristics:Год выпуска', 'Weight', 'Length', 'Width', 'Height'));

    $xml = simplexml_load_file($export);
    $array = json_decode(json_encode($xml), true);
    foreach ($array['cars']['car'] as $car){
        
        $re = '/Комплектация:(.*\n*\s*)✅-Предоставим/imsU';
        preg_match_all($re, $car['description'], $matches, PREG_SET_ORDER, 0);
        $description = trim($matches[0][1]);
        $pre_description = "";
        $pre_description = '<span style="font-weight: 400;">Год выпуска: '. $car['year'] .'</span>г.в.<br>';
        $pre_description .= '<p style="text-align: left;"><span style="font-weight: 400;">Пробег: </span>'. $car['run'] .' км</p>';
        $pre_description .= '<span style="font-weight: 400;">Тип кузова: '. $car['body_type'] .'</span><br>';
        $pre_description .= '<span style="font-weight: 400;">Трансмиссия: </span>' . $car['transmission'];
        if (gettype($car['extras']) == 'string') {
            $description .= "<br>" . $car['extras'];
        }
        $car_element = array(
            'Brand' => $car['mark_id'],
            'SKU' => "",
            'Mark' => "",
            'Category' => "Все автомобили;".$car['mark_id'],
            'Title' => $car['mark_id']." ".$car['folder_id'],
            'Description' => $pre_description,
            'Text' => str_replace(PHP_EOL, '<br>', $description),
            'Photo' => implode(" ", $car['images']['image']),
            'Price' => $car['price'],
            'Quantity' => "",
            'Price Old' => "",
            'Editions' => "",
            'Modifications' => "",
            'External ID' => $car['vin'],
            'Parent UID' => "",
            'Characteristics:Двигатель' => $car['modification_id'],
            'Characteristics:Состояние' => $car['state'],
            'Characteristics:Модель автомобиля' => "",
            'Characteristics:Пробег' => $car['run']." км",
            'Characteristics:Тип кузова' => $car['body_type'],
            'Characteristics:Трансмиссия' => $car['transmission'],
            'Characteristics:Руль' => $car['wheel'],
            'Characteristics:Привод' => $car['drive'],
            'Characteristics:Цвет' => $car['color'],
            'Characteristics:Год выпуска' => $car['year'],
            'Weight' => 0,
            'Length' => 0,
            'Width' => 0,
            'Height' => 0
        );
        foreach ($car_element as $key => $value) {
            if (gettype($value) != 'string') {
                $car_element[$key] = "";
            }
        }
        $cars[] = $car_element;

    }

    create_csv_file($cars, __DIR__.'/to_import.csv');
?>