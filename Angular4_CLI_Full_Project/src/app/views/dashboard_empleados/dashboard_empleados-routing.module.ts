import { NgModule } from '@angular/core';
import { Routes,
     RouterModule } from '@angular/router';

import { Dashboard_empleadosComponent } from './dashboard_empleados.component';

const routes: Routes = [
  {
    path: '',
    component: Dashboard_empleadosComponent,
    data: {
      title: 'Dashboard empleados'
    }
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class Dashboard_empleadosRoutingModule {}
