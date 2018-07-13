import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';


import { compraComponent } from './compra/compra.component';
import { comprasComponent } from './compras/compras.component';


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
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class comprasRoutingModule {}
