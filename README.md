# POS Transaction Sync & Inventory Monitor

### 📌 Descripción del Proyecto
Herramienta de observabilidad de datos y auditoría transaccional desarrollada en **Laravel**. Diseñada específicamente para mitigar y resolver la pérdida de consistencia de datos en entornos de venta minorista (Retail) que operan con sistemas de facturación de contingencia offline. El sistema monitorea, audita y valida que el 100% de los pedidos registrados localmente se sincronicen correctamente con el ERP contable central (Perseo ERP).

### 🛠️ Problema de Negocio Resuelto
Debido a la conectividad intermitente en los puntos de venta, los pedidos registrados fuera de línea a menudo no impactaban el ERP central de forma automática. Esto generaba dos problemas críticos para la operación del comisariato:
1. **Desfase de Inventario:** Los productos vendidos no se daban de baja inmediatamente en el sistema central, provocando descuadres físicos en perchas y bodegas.
2. **Inconsistencia Financiera:** El flujo de caja, las facturas emitidas y los cobros por tarjetas (vouchers) no se reflejaban a tiempo en la contabilidad general para las auditorías de caja.

### 💡 Solución de Ingeniería con Laravel
Desarrollé un monitor centralizado que actúa como una capa de conciliación de datos (*Data Reconciliation Layer*), estructurado bajo los siguientes componentes del framework:

*   **Artisan Console Commands:** Tareas programadas en segundo plano (*Scheduled Cron Jobs*) que extraen y contrastan los registros transaccionales entre las bases de datos locales y el ERP central.
*   **Query Builder / Eloquent ORM:** Consultas optimizadas encargadas de realizar cruces de información complejos para identificar de forma exacta qué IDs de pedidos se encuentran huérfanos o pendientes de facturación.
*   **Data Patching & Alerts Panel:** Interfaz web administrativa para la visualización en tiempo real de discrepancias en costos, cantidades erróneas y facturas pendientes, permitiendo al equipo técnico y contable aplicar correcciones seguras y mantener la integridad del negocio.

### 🚀 Tecnologías y Herramientas Utilizadas
*   **Framework:** Laravel 10+ / PHP 8+
*   **Bases de Datos:** MySQL / SQLite / Consultas avanzadas de integración con Perseo ERP
*   **Arquitectura:** Desarrollo guiado y acelerado por asistentes de Inteligencia Artificial (AI-Native Engineering Workflow)
