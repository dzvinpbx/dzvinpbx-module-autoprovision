[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0) [![GitHub Release](https://img.shields.io/github/v/release/dzvinpbx/dzvinpbx-module-autoprovision)](https://github.com/dzvinpbx/dzvinpbx-module-autoprovision/releases) [![PHP 8.4](https://img.shields.io/badge/PHP-8.4-777BB4.svg)](https://www.php.net/) [![Dzvin PBX 2025.1.1+](https://img.shields.io/badge/Dzvin%20PBX-2025.1.1+-1DBF73.svg)](https://github.com/dzvinpbx) [![Issues](https://img.shields.io/github/issues/dzvinpbx/dzvinpbx-module-autoprovision)](https://github.com/dzvinpbx/dzvinpbx-module-autoprovision/issues)

**[Українська](README.uk.md)** | [English](README.md) | [Русский](README.ru.md)

# ModuleAutoprovision

<img src="public/assets/img/logo.png" alt="ModuleAutoprovision" width="160">

Модуль автоматической настройки IP-телефонов для Dzvin PBX. Обнаруживает телефоны в локальной сети через Plug-and-Play (PnP) мультикаст, генерирует конфигурационные файлы для каждого производителя и доставляет их по HTTP.

## Возможности

- Автоматическое обнаружение и регистрация телефонов через SIP PnP (мультикаст `224.0.1.75:5060`)
- Генерация конфигураций под Yealink, Snom, Fanvil
- Общая телефонная книга для Yealink (XML) и Grandstream (текст)
- Шаблонизатор с подстановкой переменных (`{SIP_USER_NAME}`, `{SIP_NUM}`, `{SIP_PASS}`)
- Маршрутизация по URI-шаблонам с подстановочным символом `%`
- Белый и чёрный списки MAC-адресов
- Настройка кнопок BLF (Busy Lamp Field)
- Дополнительные INI-параметры под каждого производителя
- REST API для отдачи конфигов и картинок
- Встроенная служебная SIP-учётка `apv-dzvin-pbx` для первичного контакта с телефонами

## Поддерживаемые телефоны

| Производитель | Модели / Примечания |
|---|---|
| **Yealink** | T18P, T19D, T21D, T28P, W52P (DECT) |
| **Snom** | Все модели с поддержкой PnP |
| **Fanvil** | Все модели с поддержкой PnP |
| **Grandstream** | Только телефонная книга |

## Установка

### Из маркетплейса Dzvin PBX

1. Откройте веб-интерфейс Dzvin PBX.
2. Перейдите в **Модули** > **Маркетплейс**.
3. Найдите **ModuleAutoprovision** и нажмите **Install**.
4. После установки активируйте модуль в разделе **Модули** > **Установленные**.

### Ручная установка

1. Скачайте `.zip` архив из [GitHub Releases](https://github.com/dzvinpbx/dzvinpbx-module-autoprovision/releases).
2. В веб-интерфейсе Dzvin PBX перейдите в **Модули** > **Установленные**.
3. Нажмите **Загрузить модуль** и выберите скачанный архив.
4. Активируйте модуль после установки.

## Принцип работы

1. Модуль создаёт на АТС служебную SIP-учётку **apv-dzvin-pbx**.
2. Worker `WorkerProvisioningServerPnP` слушает мультикаст `224.0.1.75:5060` и отвечает на PnP `SUBSCRIBE` от телефонов.
3. Сброшенный к заводским настройкам телефон в LAN находит АТС и регистрируется как **apv-dzvin-pbx**.
4. Пользователь набирает с телефона шаблон провижининга (например `*2*XXX`), где `XXX` — желаемый внутренний номер.
5. Телефон скачивает свой конфиг с `/pbxcore/api/autoprovision-http/...` и заново регистрируется уже под привязанным внутренним номером.

## Настройка

После активации модуля откройте **Модули** > **Модуль автоматической настройки телефонов**. Страница настроек содержит пять вкладок:

| Вкладка | Описание |
|---|---|
| **Настройки телефонов** | Привязка сотрудников к MAC-адресам и шаблонам |
| **Шаблоны настроек** | Шаблоны конфигурации с подстановкой переменных |
| **Настройки URI** | Маршрутизация URI-шаблонов к шаблонам (поддержка `%`) |
| **Телефонная книга** | Адреса внешних АТС для общей телефонной книги |
| **PnP настройка** | Шаблон номера, адрес сервера, списки MAC, доп. параметры |

### Переменные шаблона

В любом шаблоне доступны:

- `{SIP_USER_NAME}` -- отображаемое имя пользователя
- `{SIP_NUM}` -- внутренний номер
- `{SIP_PASS}` -- SIP-пароль
- `{PBX_HOST}` -- хост / IP АТС из PnP-настроек

## REST API

Модуль предоставляет две группы эндпоинтов:

### Внутренние (с авторизацией)

| Метод | Эндпоинт | Описание |
|---|---|---|
| GET | `/pbxcore/api/autoprovision/getcfg?mac=<MAC>` | Сгенерировать и отдать конфиг для указанного MAC |
| GET | `/pbxcore/api/autoprovision/getimg?file=<name>` | Отдать картинку из `assets/img/` |

### Публичные (без авторизации, для телефонов)

Базовый путь: `/pbxcore/api/autoprovision-http`

| Эндпоинт | Описание |
|---|---|
| `/{p1}/{p2}/.../{p35}` | Выдача конфига по URI-шаблону (автоопределение производителя по `User-Agent` / MAC) |
| `/phonebook`, `/yealink` | Телефонная книга в формате Yealink XML |
| `/grandstream` | Телефонная книга в формате Grandstream (текст) |

## Требования

- Dzvin PBX **2025.1.1** или выше
- PHP 8.4

## Поддержка

- **Issues**: [GitHub Issues](https://github.com/dzvinpbx/dzvinpbx-module-autoprovision/issues)

## Лицензия

GPL-3.0-or-later. См. [LICENSE](LICENSE).

## Происхождение

Это форк Dzvin PBX модуля [`mikopbx/ModuleAutoprovision`](https://github.com/mikopbx/ModuleAutoprovision)
(от тега `v1.66`, коммит `3020c76`), (c) MIKO LLC, Alexey Portnov и Nikolay Beketov, лицензия
GPL-3.0-or-later. Форк переименовывает пространство имён ядра АТС (`MikoPBX\` -> `DzvinPBX\`),
дополняет украинский интерфейс и переводит ссылки и релизы на этот репозиторий. Оригинальные
заголовки авторских прав и лицензии в каждом файле кода сохранены без изменений; ядро, для которого
написан модуль, — [MikoPBX Core](https://github.com/mikopbx/Core).
