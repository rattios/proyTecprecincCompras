import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';


import { presupuestoComponent } from './presupuesto/presupuesto.component';
import { presupuestosComponent } from './presupuestos/presupuestos.component';


const routes: Routes = [
  {
    path: '',
    data: {
      title: 'Presupuestos'
    },
    children: [
      {
        path: 'presupuesto',
        component: presupuestoComponent,
        data: {
          title: 'Presupuesto'
        }
      },
      {
        path: 'presupuestos',
        component: presupuestosComponent,
        data: {
          title: 'Presupuesto'
        }
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class presupuestosRoutingModule {}
