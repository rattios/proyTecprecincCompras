import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';


import { compraComponent } from './compra/compra.component';
import { comprasComponent } from './compras/compras.component';
import { compras_recepcionComponent } from './compras_recepcion/compras_recepcion.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'compras'
    },
    children: [
      {
        path: 'compra',
        component: compraComponent,
        data: {
          title: 'compra'
        }
      },
      {
        path: 'compras',
        component: comprasComponent,
        data: {
          title: 'compra'
        }
      },
      {
        path: 'compras_recepcion',
        component: compras_recepcionComponent,
        data: {
          title: 'compras_recepcion'
        }
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class comprasRoutingModule {}
