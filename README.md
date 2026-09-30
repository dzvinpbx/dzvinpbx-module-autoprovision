[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0) [![GitHub Release](https://img.shields.io/github/v/release/dzvinpbx/dzvinpbx-module-autoprovision)](https://github.com/dzvinpbx/dzvinpbx-module-autoprovision/releases) [![PHP 8.4](https://img.shields.io/badge/PHP-8.4-777BB4.svg)](https://www.php.net/) [![Dzvin PBX 2025.1.1+](https://img.shields.io/badge/Dzvin%20PBX-2025.1.1+-1DBF73.svg)](https://github.com/dzvinpbx) [![Issues](https://img.shields.io/github/issues/dzvinpbx/dzvinpbx-module-autoprovision)](https://github.com/dzvinpbx/dzvinpbx-module-autoprovision/issues)

**[Українська](README.uk.md)** | [English](README.md) | [Русский](README.ru.md)

# ModuleAutoprovision

<img src="public/assets/img/logo.png" alt="ModuleAutoprovision" width="160">

Automatic IP phone provisioning module for Dzvin PBX. Discovers phones on the local network via Plug-and-Play (PnP) multicast, generates vendor-specific configuration files, and delivers them over HTTP.

## Features

- Automatic phone discovery and registration via SIP PnP (multicast `224.0.1.75:5060`)
- Vendor-specific configuration generation for Yealink, Snom, Fanvil
- Shared phonebook for Yealink (XML) and Grandstream (text)
- Template engine with variable substitution (`{SIP_USER_NAME}`, `{SIP_NUM}`, `{SIP_PASS}`)
- URI pattern matching with `%` wildcard for flexible delivery
- MAC address whitelist / blacklist
- BLF (Busy Lamp Field) button configuration
- Per-vendor INI overrides
- REST API for configuration and image delivery
- Built-in SIP user `apv-dzvin-pbx` for first contact with factory-reset phones

## Supported phones

| Vendor | Models / Notes |
|---|---|
| **Yealink** | T18P, T19D, T21D, T28P, W52P (DECT) |
| **Snom** | All PnP-capable models |
| **Fanvil** | All PnP-capable models |
| **Grandstream** | Phonebook only |

## Installation

### From the Dzvin PBX Marketplace

1. Open the Dzvin PBX web interface.
2. Navigate to **Modules** > **Marketplace**.
3. Find **ModuleAutoprovision** in the list and click **Install**.
4. Once installed, enable the module on the **Modules** > **Installed** page.

### Manual installation

1. Download the latest `.zip` release from the [Releases](https://github.com/dzvinpbx/dzvinpbx-module-autoprovision/releases) page.
2. In the Dzvin PBX web interface, go to **Modules** > **Installed**.
3. Click **Upload module** and select the downloaded `.zip` file.
4. Enable the module after installation.

## How it works

1. The module creates a service SIP account **apv-dzvin-pbx** on the PBX.
2. The `WorkerProvisioningServerPnP` worker listens on multicast `224.0.1.75:5060` and answers PnP `SUBSCRIBE` from phones.
3. A factory-reset phone in the LAN discovers the PBX and registers as **apv-dzvin-pbx**.
4. The user dials the provisioning pattern from the phone (e.g. `*2*XXX`) where `XXX` is the desired extension.
5. The phone fetches its config from `/pbxcore/api/autoprovision-http/...` and re-registers with the assigned extension.

## Configuration

After enabling the module, open **Modules** > **Autoprovision module** in Dzvin PBX. The settings page has five tabs:

| Tab | Description |
|---|---|
| **Phone Settings** | Bind employees to MAC addresses and templates |
| **Settings Templates** | Configuration templates with variable substitution |
| **URI Settings** | Map URI patterns to templates (supports `%` wildcard) |
| **Phone Book** | Add external PBX addresses for shared phonebook |
| **PnP Settings** | Extension pattern, PBX host, MAC lists, additional params |

### Template variables

Inside any template you can use:

- `{SIP_USER_NAME}` -- display name of the user
- `{SIP_NUM}` -- extension number
- `{SIP_PASS}` -- SIP secret
- `{PBX_HOST}` -- PBX host / IP from PnP settings

## REST API

Two endpoint families are exposed:

### Internal (authenticated) endpoints

| Method | Endpoint | Description |
|---|---|---|
| GET | `/pbxcore/api/autoprovision/getcfg?mac=<MAC>` | Generate and stream the vendor config for the given MAC |
| GET | `/pbxcore/api/autoprovision/getimg?file=<name>` | Serve an image asset from `assets/img/` |

### Public (no-auth) endpoints used by phones

Base path: `/pbxcore/api/autoprovision-http`

| Endpoint | Description |
|---|---|
| `/{p1}/{p2}/.../{p35}` | URI-matched configuration delivery (auto-detects vendor by `User-Agent` / MAC) |
| `/phonebook`, `/yealink` | Phonebook in Yealink XML format |
| `/grandstream` | Phonebook in Grandstream text format |

## Requirements

- Dzvin PBX **2025.1.1** or later
- PHP 8.4

## Support

- **Issues**: [GitHub Issues](https://github.com/dzvinpbx/dzvinpbx-module-autoprovision/issues)

## License

GPL-3.0-or-later. See [LICENSE](LICENSE).

## Origin

This is a Dzvin PBX fork of [`mikopbx/ModuleAutoprovision`](https://github.com/mikopbx/ModuleAutoprovision)
(from tag `v1.66`, commit `3020c76`), (c) MIKO LLC, Alexey Portnov and Nikolay Beketov, licensed
GPL-3.0-or-later. The fork renames the PBX core namespace the module depends on (`MikoPBX\` -> `Dzvin PBX\`),
completes the Ukrainian UI strings and points links and releases at this repository. Original copyright
and licence headers are kept unchanged in every source file; the core this module is written for is
[MikoPBX Core](https://github.com/mikopbx/Core).
