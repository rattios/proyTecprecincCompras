import { NgModule } from '@angular/core';
import {CommonModule} from '@angular/common';
import { ChartsModule } from 'ng2-charts/ng2-charts';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { TabsModule } from 'ngx-bootstrap/tabs';
import { AlertModule } from 'ngx-bootstrap/alert';
import { ModalModule } from 'ngx-bootstrap/modal';

import { presupuestosRoutingModule } from './presupuestos-routing.module';

import { presupuestoComponent } from './presupuesto/presupuesto.component';


@NgModule({
  imports: [
    presupuestosRoutingModule,
    ChartsModule,
    HttpClientModule,
    CommonModule,
    FormsModule,
    TabsModule,
    AlertModule.forRoot(),
    ModalModule.forRoot(),
  ],
  declarations: [ 

    presupuestoComponent,

    ]
})
export class presupuestosModule { }