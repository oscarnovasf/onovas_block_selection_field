onovas: Block Selection Field
===

>Nombre de máquina: onovas_block_selection_field

[![version][version-badge]][changelog]
[![Licencia][license-badge]][license]
[![Código de conducta][conduct-badge]][conduct]
[![Donate][donate-badge]][donate-url]
[![wakatime](https://wakatime.com/badge/user/236d57da-61e8-46f2-980b-7af630b18f42/project/018b9b08-fb30-4aba-807e-a5ce87396e9d.svg)](https://wakatime.com/badge/user/236d57da-61e8-46f2-980b-7af630b18f42/project/018b9b08-fb30-4aba-807e-a5ce87396e9d)

---

## Información
El objetivo de este módulo es proporcionar un área dentro del theme que nos
sirva para contener todos los bloques que nos puede interesar agregar a un
tipo de contenido (mediante un campo de referencia a entidad).

---

## Requisitos
Este módulo necesita para su correcto funcionamiento una versión superior
a la 10.x de Drupal.

---

## Instalación
Este módulo se instala como cualquier otro módulo de Drupal.  
No es necesario un proceso de instalación más avanzado.

Se recomienda, eso sí, instalarlo en la ruta **modules/custom/** para que se
instale la traducción al castellano.

---

## Configuración
- En la configuración del módulo elegimos el theme al que aplicar el bloque.
- En el tipo de contenido añadimos un campo de tipo referencia a bloque, y le
  ponemos como método de referencia el que ha creado este módulo.
- En la pestaña de "Gestionar presentación" del campo, debemos ponerle como
  formato: Entidad Representada.

---
⌨️ con ❤️ por [Óscar Novás][mi-web] 😊

[mi-web]: https://oscarnovas.com "for developers"

[version]: v1.0.0
[version-badge]: https://img.shields.io/badge/Versión-1.0.0-blue.svg

[license]: LICENSE.md
[license-badge]: https://img.shields.io/badge/Licencia-GPLv3+-green.svg "Leer la licencia"

[conduct]: CODE_OF_CONDUCT.md
[conduct-badge]: https://img.shields.io/badge/C%C3%B3digo%20de%20Conducta-2.0-4baaaa.svg "Código de conducta"

[changelog]: CHANGELOG.md "Histórico de cambios"

[donate-badge]: https://img.shields.io/badge/Donaci%C3%B3n-PayPal-red.svg
[donate-url]: https://paypal.me/oscarnovasf "Haz una donación"
