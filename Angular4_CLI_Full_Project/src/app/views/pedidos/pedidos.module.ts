import { NgModule } from '@angular/core';
import {CommonModule} from '@angular/common';
import { ChartsModule } from 'ng2-charts/ng2-charts';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';

import { pedidosComponent } from './pedidos.component';
import { pedidosRoutingModule } from './pedidos-routing.module';

@NgModule({
  imports: [
    pedidosRoutingModule,
    ChartsModule,
    HttpClientModule,
    CommonModule,
    FormsModule
  ],
  declarations: [ pedidosComponent ]
})
export class PedidosModule { }
