import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { transferenciasComponent } from './transferencias.component';
import { transferenciasPuraComponent } from './transferencias/transferenciasPura.component';
import { devolucionesComponent } from './transferencias/devoluciones.component';
import { transferenciasPatrimonialComponent } from './transferencias/transferenciasPatrimonial.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'Transferencias'
    },
    children: [
      {
        path: 'transferencia',
        component: transferenciasComponent,
        data: {
          title: 'Transferencias'
        }
      },
      {
        path: 'transferenciaspura',
        component: transferenciasPuraComponent,
        data: {
          title: 'Transferencias Pura'
        }
      },
      {
        path: 'devoluciones',
        component: devolucionesComponent,
        data: {
          title: 'Devoluciones'
        }
      },
      {
        path: 'transferenciaspatrimonial',
        component: transferenciasPatrimonialComponent,
        data: {
          title: 'Transferencias Patrimoniales'
        }
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class transferenciasRoutingModule {}
