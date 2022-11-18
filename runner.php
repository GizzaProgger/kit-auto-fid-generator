<?php

$path_output_file = 'output';
$output_file_name = 'output.xml';
$path_to_file = 'https://kit-auto18.ru/tstore/yml/cac2996179b81db1f33e5b632135f2a3.yml';

$not_params = '';
$current_description ='Автосалон Кит 🐋 Один- из крупнейших автодилеров в Удмуртской республике, с профессиональным подходом к своему делу, мы с удовольствием проконсультируем и ответим на все ваши вопросы. Для вас мы подготовили Более 150 позиций авто и мототехники в наличии и с ПТС! Наш автосалон предлагает следующие услуги: ✅-Продажа автомобилей новых и с пробегом ✅-Выкуп вашего автомобиля (деньги сразу в день обращения наличным и безналичным расчетом!) ✅-Выкуп автомобилей из кредита и лизинга ✅-Обмен вашего авто по системе Trade-in ✅-Реализация вашего автомобиля на выгодных условиях ✅-Кредитование (более чем 16 банков – партнёров, оформление кредита по двум документам) ✅-Расчет кредита по ТЕЛЕФОНУ за 5 мин ✅-Онлайн показ автомобилей для вас в любом удобном мессенджер Комплектация: Климат-контроль 2-х зонный, круиз, салон кожа, магнитола, центральный замок, бортовой компьютер, эл/стекла, эл/зеркала, обогрев/вентиляция сидений, обогрев зеркал, мультируль, старт/стоп, парктроник, камера кругового обзора, 2 комплекта резины, литые диски, сигнализация с а/з, навигация, громкая связь, эл/привод сидений, бесключевой доступ, bluetooth, датчики дождя/света, подогрев руля, память сидений, аудиосистема bose, датчики контроля слепых зон, антирадар, регистратор, ШВУ, ЭУР, SRS, ABS, ESP. 1 владелец, гарантия, все ТО. ✅-Предоставим отчет Автотеки и VIN номер по запросу. ✅-Стоимость автомобиля обсуждается индивидуально. ✅-Гарантируем безопасность и юридическую чистоту автомобиля! ✅-Диагностика автомобиля в любом Тех. Центре нашего города по вашему желанию! А также Возможность проведения дистанционной диагностики! ✅-Скидки на обслуживание автомобиля после покупки в нашем Авто Тех Центре "КИТ" ! ✅-Если Вы не нашли подходящий автомобиль у нас, а нашли его на другой площадке или у частного продавца - мы готовы провести оформление сделки в кредит через наш автосалон всего за 6000 руб! Банк-партнер:ПАО «РОСБАНК» Генеральная лицензия ЦБ №2272 от 28.01.2015г. Условия по кредиту уточняйте по телефону или у менеджеров отдела продаж. Осмотр автомобиля происходит по адресу: УР г. Ижевск ул.10 Лет Октября 60/1 Будем рады видеть Вас в нашем автосалоне или ответить на Ваш звонок. Режим Работы: ПН-ПТ с 9:00 до 18:00, СБ c 9:00 до 17:00 и ВС c 11:00 до 15:00.';
$current_availability = 'В наличии';

$data = simplexml_load_file($path_to_file);

$xml = new DOMDocument();
$xml_data = $xml->createElement('data');
$xml_cars = $xml->createElement('cars');

if ($data && $data->shop && $data->shop->offers && count($data->shop->offers->offer)) {

    foreach ($data->shop->offers->offer as $row) {
       
        $xml_car = $xml->createElement('car');

        $xml_mark_id = $xml->createElement('mark_id');
        $mark_id = $xml->createTextNode(strval($row->vendor));

        $xml_folder_id = $xml->createElement('folder_id');
        $folder_id = $xml->createTextNode(strval($row->param[0]));

        $xml_modification_id = $xml->createElement('modification_id');
        $modification_id = $xml->createTextNode(strval($row->param[5]));

        $xml_body_type = $xml->createElement('body_type');
        $body_type = $xml->createTextNode(strval($row->param[4]));

        $xml_drive = $xml->createElement('drive');
        $drive = $xml->createTextNode(strval($row->param[9]));

        $xml_transmission = $xml->createElement('transmission');
        $transmission = $xml->createTextNode(strval($row->param[6]));

        $xml_complectation_name = $xml->createElement('complectation_name');
        $complectation_name = $xml->createTextNode($not_params);

        $xml_doors_count = $xml->createElement('doors_count');
        $doors_count = $xml->createTextNode($not_params);

        $xml_wheel = $xml->createElement('wheel');
        $wheel = $xml->createTextNode(strval($row->param[7]));

        $xml_color = $xml->createElement('color');
        $color = $xml->createTextNode(strval($row->param[10]));

        $xml_metallic = $xml->createElement('metallic');

        $xml_availability = $xml->createElement('availability');
        $availability = $xml->createTextNode($current_availability);
        
        $xml_custom = $xml->createElement('custom');
        $custom = $xml->createTextNode($not_params);

        $xml_state = $xml->createElement('state');
        $state = $xml->createTextNode(strval($row->param[8]));

        $xml_pts = $xml->createElement('pts');
        $pts = $xml->createTextNode($not_params);

        $xml_owners_number = $xml->createElement('owners_number');
        $owners_number = $xml->createTextNode($not_params);

        $xml_run = $xml->createElement('run');
        $run = $xml->createTextNode(intval($row->param[3]));

        $xml_year = $xml->createElement('year');
        $year = $xml->createTextNode(intval($row->param[2]));

        $xml_price = $xml->createElement('price');
        $price = $xml->createTextNode(intval($row->price));

        $xml_online_view_available = $xml->createElement('online_view_available');
        $online_view_available = $xml->createTextNode('true');

        $xml_booking_allowed = $xml->createElement('booking_allowed');
        $booking_allowed = $xml->createTextNode($not_params);

        $xml_currency = $xml->createElement('currency');
        $currency = $xml->createTextNode(strval($row->currencyId));

        $xml_registry_year = $xml->createElement('registry_year');
        $registry_year = $xml->createTextNode(intval($row->param[2]));

        $xml_vin = $xml->createElement('vin');
        $vin = $xml->createTextNode($not_params);

        $xml_extras = $xml->createElement('extras');
        $finding = '<br />';
        $num = strrpos(strval($row->description), $finding);
        $extras = $xml->createTextNode(substr(strval($row->description), $num+6));

        $xml_unique_id = $xml->createElement('unique_id');
        $unique_id = $xml->createTextNode(intval($row['id']));

        $xml_images = $xml->createElement('images');
        $xml_image = $xml->createElement('image');
        $image = $xml->createTextNode(strval($row->picture));

        $xml_video = $xml->createElement('video');
        $video = $xml->createTextNode($not_params);

        $xml_action = $xml->createElement('action');
        $action = $xml->createTextNode($not_params);

        $xml_autoru_expert = $xml->createElement('autoru_expert');
        $autoru_expert = $xml->createTextNode($not_params);

        $xml_poi_id = $xml->createElement('poi_id');
        $poi_id = $xml->createTextNode($not_params);

        $xml_sale_services = $xml->createElement('sale_services');
        $sale_services = $xml->createTextNode($not_params);

        $xml_contact_info = $xml->createElement('contact_info');
        $xml_contact = $xml->createElement('contact');
        $xml_name = $xml->createElement('name');
        $xml_phone = $xml->createElement('phone');
        $xml_time = $xml->createElement('time');
        $name = $xml->createTextNode($not_params);
        $phone = $xml->createTextNode($not_params);
        $time = $xml->createTextNode($not_params);

        $xml_user_id = $xml->createElement('user_id');
        $user_id = $xml->createTextNode($not_params);

        $xml_description = $xml->createElement('description');
        $description = $xml->createTextNode($current_description);


        $xml_mark_id->appendChild($mark_id);
        $xml_folder_id->appendChild($folder_id);
        $xml_modification_id->appendChild($modification_id);
        $xml_body_type->appendChild($body_type);
        $xml_drive->appendChild($drive);
        $xml_transmission->appendChild($transmission);
        $xml_complectation_name->appendChild($complectation_name);
        $xml_doors_count->appendChild($doors_count);
        $xml_wheel->appendChild($wheel);
        $xml_color->appendChild($color);
        $xml_availability->appendChild($availability);
        $xml_custom->appendChild($custom);
        $xml_state->appendChild($state);
        $xml_pts->appendChild($pts);
        $xml_owners_number->appendChild($owners_number);
        $xml_run->appendChild($run);
        $xml_year->appendChild($year);
        $xml_price->appendChild($price);
        $xml_online_view_available->appendChild($online_view_available);
        $xml_booking_allowed->appendChild($booking_allowed);
        $xml_currency->appendChild($currency);
        $xml_registry_year->appendChild($registry_year);
        $xml_vin->appendChild($vin);
        $xml_extras->appendChild($extras);
        $xml_unique_id->appendChild($unique_id);
        $xml_image->appendChild($image);
        $xml_images->appendChild($xml_image);
        $xml_video->appendChild($video);
        $xml_action->appendChild($action);
        $xml_autoru_expert->appendChild($autoru_expert);
        $xml_poi_id->appendChild($poi_id);
        $xml_sale_services->appendChild($sale_services);
        $xml_name->appendChild($name);
        $xml_phone->appendChild($phone);
        $xml_time->appendChild($time);
        $xml_contact->appendChild($xml_name);
        $xml_contact->appendChild($xml_phone);
        $xml_contact->appendChild($xml_time);
        $xml_contact_info->appendChild($xml_contact);
        $xml_user_id->appendChild($user_id);
        $xml_description->appendChild($description);

        $xml_car->appendChild($xml_mark_id);
        $xml_car->appendChild($xml_folder_id);
        $xml_car->appendChild($xml_modification_id);
        $xml_car->appendChild($xml_body_type);
        $xml_car->appendChild($xml_drive);
        $xml_car->appendChild($xml_transmission);
        $xml_car->appendChild($xml_complectation_name);
        $xml_car->appendChild($xml_doors_count);
        $xml_car->appendChild($xml_wheel);
        $xml_car->appendChild($xml_color);
        $xml_car->appendChild($xml_metallic);
        $xml_car->appendChild($xml_availability);
        $xml_car->appendChild($xml_custom);
        $xml_car->appendChild($xml_state);
        $xml_car->appendChild($xml_pts);
        $xml_car->appendChild($xml_owners_number);
        $xml_car->appendChild($xml_run);
        $xml_car->appendChild($xml_year);
        $xml_car->appendChild($xml_price);
        $xml_car->appendChild($xml_online_view_available);
        $xml_car->appendChild($xml_booking_allowed);
        $xml_car->appendChild($xml_currency);
        $xml_car->appendChild($xml_registry_year);
        $xml_car->appendChild($xml_vin);
        $xml_car->appendChild($xml_extras);
        $xml_car->appendChild($xml_unique_id);
        $xml_car->appendChild($xml_images);
        $xml_car->appendChild($xml_video);
        $xml_car->appendChild($xml_action);
        $xml_car->appendChild($xml_autoru_expert);
        $xml_car->appendChild($xml_poi_id);
        $xml_car->appendChild($xml_sale_services);
        $xml_car->appendChild($xml_contact_info);
        $xml_car->appendChild($xml_user_id);
        $xml_car->appendChild($xml_description);

        $xml_cars->appendChild($xml_car);
    }

    $xml_data->appendChild($xml_cars);
    $xml->appendChild($xml_data);

    $xml->save($path_output_file . '/' . $output_file_name);

    print_r('finished');

} else {
    print_r('not found data');
}




  

  