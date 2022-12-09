#!/usr/bin/env bash

echo "Ты скопировал фото из storage/app/public/photo/? y/n"
read answer

if [ $answer == "y" ]; then
    echo "Ну поехали..."

    rm -rf ../gen-old
    cp -a ../old ../gen-old

    cd ../gen-old
    rm -rf storage/app/public/photo
    mkdir storage/app/public/photo
    rm .env
    cp .env.production .env
    rm .env.production
    rm public/storage

    if ! [ -d node_modules/ ]; then
    npm install
    fi

    npm run production
    rm -rf node_modules

    composer update --optimize-autoloader --no-dev

    rm -rf storage/logs/laravel.log
    touch storage/logs/laravel.log
    chmod +777 storage/logs/laravel.log

    php artisan cache:clear

    php artisan route:cache
    php artisan config:cache
    php artisan view:cache
    php artisan event:cache

    php -r '$fileName = __DIR__. "/bootstrap/cache/config.php";
        $fileContent = file_get_contents($fileName);

        $basePath = __DIR__;
        $fileContent = str_replace($basePath, "/var/www/danshin_gen/old", $fileContent);

        file_put_contents($fileName, $fileContent);
    '

    cd ../

    tar -cf gen-old.tar gen-old

    scp gen-old.tar root@5.35.93.48:/var/www/danshin_gen
    ssh root@5.35.93.48 rm -r /var/www/danshin_gen/old
    ssh root@5.35.93.48 tar -C /var/www/danshin_gen -xvf /var/www/danshin_gen/gen-old.tar
    ssh root@5.35.93.48 rm -rf /var/www/danshin_gen/gen-old.tar
    ssh root@5.35.93.48 mv /var/www/danshin_gen/gen-old /var/www/danshin_gen/old
    ssh root@5.35.93.48 ln -s /var/www/danshin_gen/old/storage/app/public /var/www/danshin_gen/old/public/storage
    ssh root@5.35.93.48 mkdir /var/www/danshin_gen/old/storage/app/public/download

    ssh root@5.35.93.48 mkdir /var/www/danshin_gen/old/storage/app/public/photo_temp
    scp ../../../"disk/Мои документы/projects/app/danshin_gen/old/backup/photo.zip" root@5.35.93.48:/var/www/danshin_gen/old/storage/app/public
    ssh root@5.35.93.48 unzip /var/www/danshin_gen/old/storage/app/public/photo.zip -d /var/www/danshin_gen/old/storage/app/public
    ssh root@5.35.93.48 rm -rf /var/www/danshin_gen/old/storage/app/public/photo.zip
    ssh root@5.35.93.48 chmod -R 777 /var//www/danshin_gen/old/storage

    rm -rf gen-old
    rm -rf gen-old.tar

    echo "files \"gen-old\" compiled successfully"
else
    echo "Иди и копируй, а потом приходи"
fi