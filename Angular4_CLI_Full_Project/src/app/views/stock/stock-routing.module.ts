import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { stockComponent } from './stock.component';

const routes: Routes = [
  {
    path: '',
    component: stockComponent,
    data: {
      title: 'stock'
    }
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class stockRoutingModule {}
