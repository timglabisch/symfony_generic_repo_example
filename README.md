### Beispielanwendung für ein halbwegs flexible Repositories

Das Repository beinhaltet eine kleine JSON-ÜBER-HTTP-API welche ein CRUD Interface für das Entity "Document" bietet.
Ds Entity besitzt mehr oder weniger nur einen Title als Attribut, zur Veranschaulichtung der gewählten Strategie ist dies aber vermutlich ausreichend.

## Ordnerstruktur

- ApiBundle -> Beinhaltet die Controller welche für die API benötigt werden.
- Document -> Beinhaltet Domänenspezifische Klassen / Interfaces
- Document*Bundle -> Beinhaltet verschiedene Repository Implementationen.

Die Grundidee ist, dass sich die eigentlichen Implementationen einfach tauschen und decorieren lassen.

Beispiel:

legt man über einen POST Request ein neues Dokument an:


```
POST http://192.168.10.22/app_dev.php
{"title": "test123"}
```

So bekommt man das passende Dokument zurückgeliefert:

```
{"document":{"id":"1e07859f-d2e5-11e6-9e68-0800274f7b35","title":"test123","created":"05.01.2017","type":"Foo\\DocumentMysqlDoctrineBundle\\Entity\\MysqlDoctrineDocument"}}
```

Schaut man sich das Resultat genau an, stellt man fest, dass der "type" mit angegeben ist.

Es wird abwechselnd zwischen Doctrine und Eloquent gewählt gewechselt, das spannende Dabei ist, dass weder die
RoundRobin, noch die Doctrine oder Elowuent Lösung voneinander wissen, diese jedoch alle zusammen arbeiten.

Dies könnte man beliebig erweitern, beispielsweise könnte man eine Imlementation schaffen, welche nur loggt, als Chaos Monkey fungiert, als zusätzlicher Security Layer,
statistiken einsammelt, unterschiedlichste caches implementiert o. etc.

Vorteile:
- Die Unterschiedlichen Implementationen haben keine Abhängigkeit zueinander.
- Es ist sehr einfach möglich andere Implementationen zu schaffen.
- Dritte Systeme lassen sich einfach anbinden, beispielsweise via protobuf, thrift oder json-rpc.
- Code ist einfach zu testen.
- Man verhinder jegliche Form von Vendor lock-in.
- Die Datenbankstruktur bleibt einfach, da Services zwangsläufig stark isoliert werden.

Nachteile:
- Abhängigkeiten zu einem Domänen Paket werden aufgebaut (src/Foo/Document).
- Erfordert gründliche Arbeit, Tools wie Deptrac helfen hier jedoch.

## Ausporbieren?

```
vagrant up
vagrant ssh
cd /opt/proj && composer install
php bin/console do:da:cr
php bin/console do:sch:up --force
```

danach sollte `http://192.168.10.22/app_dev.php/` aufrufbar sein.


## Requests

```
    {
        "name": "document list",
        "request": {
            "url": "http://192.168.10.22/app_dev.php",
            "method": "GET",
            "header": [],
            "body": {},
            "description": ""
        },
        "response": []
    },
    {
        "name": "document post",
        "request": {
            "url": "http://192.168.10.22/app_dev.php",
            "method": "POST",
            "header": [],
            "body": {
                "mode": "raw",
                "raw": "{\n\t\"title\": \"test123\"\n}"
            },
            "description": ""
        },
        "response": []
    },
    {
        "name": "document get",
        "request": {
            "url": "http://192.168.10.22/app_dev.php/1e07859f-d2e5-11e6-9e68-0800274f7b35",
            "method": "GET",
            "header": [],
            "body": {
                "mode": "raw",
                "raw": "{\n\t\"title\": \"test123\"\n}"
            },
            "description": ""
        },
        "response": []
    },
    {
        "name": "document delete",
        "request": {
            "url": "http://192.168.10.22/app_dev.php/586d9a120684c",
            "method": "DELETE",
            "header": [],
            "body": {
                "mode": "raw",
                "raw": "{\n\t\"title\": \"test123\"\n}"
            },
            "description": ""
        },
        "response": []
    },
    {
        "name": "document put",
        "request": {
            "url": "http://192.168.10.22/app_dev.php/b0cad4e5-d2d7-11e6-9844-0800274f7b35",
            "method": "PUT",
            "header": [],
            "body": {
                "mode": "raw",
                "raw": "{\n\t\"title\": \"test123\"\n}"
            },
            "description": ""
        },
        "response": []
    }

```
