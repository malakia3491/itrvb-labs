Автозагрузчик 

Реализуйте автозагрузчик классов согласно следующим правилам:

1. Разделитель пространства имён преобразуется в разделитель папок: / для Linux и MacOS или \ для Windows. 

2. Знак _ в имени класса преобразуется в разделитель папок. 

3. Файл с кодом класса имеет расширение .php. 

Примеры: 
1. \Doctrine\Common\ClassLoader ⇒ /some/path/Doctrine/Common/ClassLoader.php. 
2. \my\package\Class_Name ⇒ /some/path/namespace/package/Class/Name.php. 
3. \my\package_name\Class_Name ⇒ /some/path/my/package_name/Class/Name.php.

Создайте простые классы:

* для пользователей: id, имя, фамилия;
* статей: id, id автора, заголовок, текст;
* комментариев: id, id автора, id статьи, текст.
	
2. Инициализируйте в проекте composer и настройте автозагрузку PSR-4, код классов положите в папку src.
3. Подключите к проекту пакет fakerphp/faker.