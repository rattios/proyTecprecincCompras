import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';


import { presupuestoComponent } from './presupuesto/presupuesto.component';



const routes: Routes = [
  {
    path: '',
    data: {
      title: 'Presupuestos'
    },
    children: [
      {
        path: 'presupuestos',
        component: presupuestoComponent,
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
