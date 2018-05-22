import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { transferenciasComponent } from './transferencias.component';

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
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class transferenciasRoutingModule {}
