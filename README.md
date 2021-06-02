# Tickets

Для развертывания необходим docker, docker-compose

##### Init (инициализация контейнеров, создание базы, загрузка тестовых данных)
```python
 bash init.sh
```

##### Start
```python
 bash start.sh
```

##### Stop
```python
 bash stop.sh
```


### Использование

Проверить почту
http://localhost:1080/

http://localhost
#### Доступные рейсы 
    /api/v1/flights

#### Билеты в продаже по номеру рейса (id)
    /api/v1/tickets/flight-{id}

#### Купить билет
    /api/v1/tickets/buy
    POST
    {
        "ticketId": 10,
        "passenger": {
            "email": "new-user@mail.com",
            "firstname": "Ivan",
            "lastname": "Ivanov",
            "passNumber": "4322-22-333-45"
        }
    }
    Ответ 201 (пассажир будет добавлен в базу, если его нет)
        {
            "flight": {
                "company": "AER",
                "departure": "Moscow",
                "arrival": "Berlin",
                "departure_time": "2021-03-25 02:03:28"
            },
            "passenger": {
                "firstname": "Ivan",
                "lastname": "Ivanov",
                "pass_number": "4322-22-333-45"
            },
            "ticket": {
                "id": 105,
                "seat": 105,
                "date_of_sale": "2021-03-22 02:03:12",
                "status": "sold"
            }
        }
    400 {"error": "error description"}

#### Забронировать
    /api/v1/tickets/book
    POST
    Структуры те же, только в билете "status": "booked"

#### Отменить бронь
    /api/v1/tickets/cancel-reservation
    POST
    {
        "ticketId": 20,
        "passenger": {
            "email": "new-user@mail.com",
            "firstname": "Ivan",
            "lastname": "Ivanov",
            "passNumber": "4322-22-333-45"
        }
    }
    Ответ 200, 400

#### События
###### Продажа завершена
    /api/v1/callback/events
    POST

    
    {"data":{
        "flight_id":1,
        "triggered_at":1585012345,
        "event":"flight_ticket_sales_completed",
        "secret_key":"a1b2c3d4e5f6a1b2c3d4e5f6"
        }
    }

###### Рейс отменён (рассылку можно увидеть http://localhost:1080)
    {"data":{
        "flight_id":3,
        "triggered_at":1585012345,
        "event":"flight_canceled",
        "secret_key":"a1b2c3d4e5f6a1b2c3d4e5f6"
        }
    }



## License
[MIT](https://choosealicense.com/licenses/mit/)
