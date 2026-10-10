# Decisiones técnicas

Registro corto de las decisiones del proyecto: qué se decidió, por qué y qué se descartó.
Cada decisión nueva se agrega al final con su fecha.

---

## 1. Seguir en WordPress

**Estado:** propuesta (pendiente de confirmar con la ingeniera)

- El sitio actual, el hosting (PHP + MySQL), el agendamiento y la orden de servicio ya funcionan sobre WordPress.
- El taller ya administra el sitio desde el escritorio de WordPress.
- **Descartado:** Laravel o Node como backend aparte. Obligaría a migrar agendamiento y OT, manejar dos sistemas de usuarios y, en el caso de Node, cambiar de hosting.

## 2. Tema propio en lugar de Elementor para las páginas nuevas

**Estado:** propuesta

- Las interacciones de los diseños (selector gasolina/eléctrica, comparador, tacómetro) necesitan código propio.
- Un tema en archivos se puede versionar en Git; Elementor guarda los diseños en la base de datos.
- Página más liviana y rápida.
- **A cambio:** el taller puede editar textos y precios, pero no rediseñar secciones.

## 3. La lógica va en plugins, el tema solo presenta

**Estado:** aceptada

- `bullracing-core`: tarifas, rol "Taller", panel de precios y API REST.
- Si se cambia el tema, nada de la lógica se pierde.

## 4. Agendamiento y OT se mantienen

**Estado:** aceptada (10/10/2026)

- Son productos de la fase con TecnoParque y ya funcionan.
- El rediseño es solo de la landing. Se sugiere (opcional) ajustar únicamente la apariencia del agendamiento para que combine con el diseño escogido, sin cambiar campos, precios ni funcionamiento.

## 5. Tarifas en la base de datos

**Estado:** propuesta

- Hoy las tarifas están escritas en el JavaScript del agendamiento.
- Se propone una tabla de tarifas administrada desde el panel del taller; landing y agendamiento leen de ahí.
- El precio final se recalcula en el servidor, no se confía en el que envía el navegador.

## Pendientes por decidir

- [ ] ¿Las tablas `agendamientos` y `ordenes_servicio` siguen en su base de datos aparte (`bullracing_bd`) o pasan a la base de WordPress? (con John y la ingeniera)
- [ ] Diseño escogido para la landing (Taller industrial, Racing o Contraste claro, o una mezcla).
- [ ] ¿TecnoParque tiene alguna regla sobre dónde deben quedar los repositorios?
- [ ] Versión de PHP/MySQL del hosting y si permite configurar SMTP.
