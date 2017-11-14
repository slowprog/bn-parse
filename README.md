# Парсинг сайта для теста

Сделано на основе своего старого простяцкого MVC-велосипеда на голом PHP.

Для запуска нужен docker и docker-compose. Запустить можно командой:

```
$ docker-compose up -d
```

После этого в браузере можно поглядеть localhost:8090.

### Некоторые нюансы

1. Не понял и не нашёл что за параметры Издание, Субъект, Контакт. Я их конечно ищу в параметрах квартиры, но ни разу не видал их, а потому может неверно и ищу.

2. Валидации никакой не прикручено. Просто на уровне html все поля сделаны обязательные для заполнения.

3. Модель данных служит источником данных из распарсиваемого сайта. Плюс станции метро хранятся в сессии пользователя.

4. Скорость работы конечно очень медленная, но вроде бы в задаче ничего про это не было, а потому я даже не парился.

5. Дезигн на скорую руку bootstrap'ом прикрутил.

6. В docker-compose.yml пришлось на скорую руку законстылить command с запуском composer сперва, а то там образ что-то не то, что я ожидал, с composer делает. Первый попавшийся в интернетах взял. Быстрее было сделать костыль =)
