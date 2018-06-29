import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { stockComponent } from './inventario/stock.component';
import { trazabilidadComponent } from './trazabilidad/trazabilidad.component';
import { departComponent } from './departamentos/depart.component';
import { empleadosComponent } from './empleados/empleados.component';

const routes: Routes = [
  
      {
        path: 'inventario',
        component: stockComponent,
        data: {
          title: 'Inventario'
        }
      },
      {
        path: 'trazabilidad',
        component: trazabilidadComponent,
        data: {
          title: 'Trazabilidad'
        }
      },
      {
        path: 'departamentos',
        component: departComponent,
        data: {
          title: 'Departamentos'
        }
      },
      {
        path: 'empleados',
        component: empleadosComponent,
        data: {
          title: 'Empleados'
        }
      }
   
];


@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class reportesRoutingModule {}
