### Beispielanwendung für  halbwegs flexible Repositories

Das Projekt beinhaltet eine kleine JSON-ÜBER-HTTP API, welche ein CRUD Interface für das Entity "Document" bietet.
Das Entity besitzt mehr oder weniger nur einen "Title" als Attribut, zur Veranschaulichtung der gewählten Strategie ist dies aber vermutlich ausreichend.

### Ordnerstruktur

- ApiBundle -> Beinhaltet die Controller, welche für die API benötigt werden.
- Document -> Beinhaltet Domänenspezifische Klassen / Interfaces
- Document*Bundle -> Beinhaltet verschiedene Repository Implementationen.

Die Grundidee ist, dass sich die eigentlichen Implementationen des Repositories/Services einfach tauschen, löschen und mit Decoratoren erweitern lassen.

Beispiel:

legt man über einen POST Request ein neues Dokument an:

```
POST http://192.168.10.22/app_dev.php
{"title": "test123"}
```

so bekommt man das passende Dokument zurückgeliefert:

```
{"document":{"id":"1e07859f-d2e5-11e6-9e68-0800274f7b35","title":"test123","created":"05.01.2017","type":"Foo\\DocumentMysqlDoctrineBundle\\Entity\\MysqlDoctrineDocument"}}
```

Schaut man sich das Resultat genau an, stellt man fest, dass der "type" mit angegeben ist.

Es wird abwechselnd zwischen Doctrine und Eloquent gewählt.
Hin und wieder wird ein Doctrine Entity zurückgeliefert, hin und wieder ein Eloquent Model.
Das spannende dabei ist, dass weder die RoundRobin-, noch die Doctrine- oder Eloquentimplementation voneinander wissen, diese jedoch alle zusammen arbeiten.

Dies könnte man beliebig erweitern, beispielsweise wären auch folgende Implementation denkbar:

- Decorator
    - Logging von Lese / Schreibzugriffe
    - Implementation eines Security Layers
    - Implementationen des Strategy-Pattern
    - Decoratoren, welche Proxys oder Decoratoren von "Document"-Entities zurückliefern
    - Implementationen für z.b. die Symfony Toolbar, einem Profiler oder ähnlichem
- Implemantionen
    - für jegliche Datenbanksenken
    - für jegliche RPC-Systeme (thrift, protobuf, json-rpc, ...)

Vorteile:

- Die unterschiedlichen Implementationen haben keine Abhängigkeit zueinander.
- Es ist sehr einfach möglich andere Implementationen zu schaffen.
- Es ist sehr einfach möglich Implementationen zu löschen.
- Es ist recht simpel mehrere Implementationen gleichzeitig zu verwenden, z.b. ein Adapter für ein Legacy System, Feature Flags, ...
- Dritte Systeme lassen sich einfach anbinden, beispielsweise via protobuf, thrift oder json-rpc.
- Code ist einfach zu testen.
- Man verhindert jegliche Form von Vendor lock-in.
- Die Datenbankstruktur bleibt einfach, da Services zwangsläufig stark isoliert werden.

Nachteile:

- Abhängigkeiten zu einem Domänen Paket werden aufgebaut (src/Foo/Document).
- Erfordert gründliche Arbeit, Tools wie Deptrac helfen hier jedoch.
- Etwas mehr Code und einige Interfaces.
- Man muss auf Breaking Changes achten (Änderungen der Interfaces in src/Document).
    - lösbar durch hohe Testabdeckung

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


## Weiteres

### Trennung von Read / Write Implementation
Bringt einige Vorteile von CQRS, setzt jedoch bewusst keinen Event-Bus ein, um ein
noch klareres Interface für Write Operationen zu haben und die Abhängigkeit zu einem sehr
generischen EventBus zu verhindern.

### Context
Das Kontextobjekt ist nicht sauber implementiert, die Grundidee hierbei ist es,
Implementationen zu ermöglichen die Stateless agieren können und auf Funktionalität
verzichten können, welche z. B. erfordert den aktuellen User, Tokens o. etc. aus dem Request zu extrahieren.
Die Implemenation ist nicht vollständig, der Ansatz ist nicht Teil des Projektes.

### Kapselung zu Symfony
Der Controller ist mehr oder weniger unabhängig von Symfony.
Die eigentlichen Implementationen registrieren nur Services - ansonsten fungieren diese nicht weiter als Bundle.
Möchte man die Abhängigkeiten weiter vermindern, wäre z. B. Puli ein Blick wert, Spryker verwendet hier auch einen weitaus isolierteren Ansatz.