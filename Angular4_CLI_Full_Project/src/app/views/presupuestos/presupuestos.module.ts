import { NgModule } from '@angular/core';
import {CommonModule} from '@angular/common';
import { ChartsModule } from 'ng2-charts/ng2-charts';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { TabsModule } from 'ngx-bootstrap/tabs';
import { AlertModule } from 'ngx-bootstrap/alert';
import { ModalModule } from 'ngx-bootstrap/modal';
import { MatDatepickerModule, MatNativeDateModule, DateAdapter } from '@angular/material';
import { DateFormat } from './date-format';


import { presupuestosRoutingModule } from './presupuestos-routing.module';

import { presupuestoComponent } from './presupuesto/presupuesto.component';
import { presupuestosComponent } from './presupuestos/presupuestos.component';

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
    MatDatepickerModule,
    MatNativeDateModule
  ],
  declarations: [ 

    presupuestoComponent,
    presupuestosComponent

    ],
    providers: [
     { provide: DateAdapter, useClass: DateFormat },
     MatNativeDateModule,
   ],
})
export class presupuestosModule { 
   constructor(private dateAdapter:DateAdapter<Date>) {
    dateAdapter.setLocale('en-in'); // DD/MM/YYYY
  }

}