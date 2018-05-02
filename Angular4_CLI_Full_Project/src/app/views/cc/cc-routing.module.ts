import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { contratosComponent } from './contratos/contratos.component';
import { centroCostosComponent } from './centroCostos/centroCostos.component';

const routes: Routes = [  
  {
    path: 'contratos',
    component: contratosComponent,
    data: {
      title: 'Contratos'
    }
  },
  {
    path: 'centroCostos',
    component: centroCostosComponent,
    data: {
      title: 'Centro costos'
    }
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ccRoutingModule {}
