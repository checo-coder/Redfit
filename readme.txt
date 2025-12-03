Los cambios son:

Dos archivos nuevos dentro de /lib/
1. /lib/gestor_citas (tiene toda la lógica de la citas por parte del médico)
2. /lib/gestor_recetas (tiene toda la lógica de recetas por parte del médico)

Cuatro archivos de la parte de admin
1. /admin/citas (la pate visual del menú de citas)
2. /admin/menu-1 (es el menú lateral que ve desde la pantalla grande como pequeña)
3. /admin/recetas_asignar (la interfaz donde aparecen los clientes y puedes asignarles las recetas)
4. /recetas/recetas_gestionar (la interfaz para que el médico agregue las recetas)

Se modificó el index.php
Ahora tiene los botones en el medio, además de que la barra de arriba ya tiene la integración para que pueda entrar
a otros apartados.
 
La base de datos se cambió.
En el archivo, digo desde que parte ahora es distinta. Se agregaron tres tablas