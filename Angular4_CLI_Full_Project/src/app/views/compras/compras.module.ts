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


import { comprasRoutingModule } from './compras-routing.module';

import { compraComponent } from './compra/compra.component';
import { comprasComponent } from './compras/compras.component';

@NgModule({
  imports: [
    comprasRoutingModule,
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

    compraComponent,
    comprasComponent

    ],
    providers: [
     { provide: DateAdapter, useClass: DateFormat },
     MatNativeDateModule,
   ],
})
export class comprasModule { 
   constructor(private dateAdapter:DateAdapter<Date>) {
    dateAdapter.setLocale('en-in'); // DD/MM/YYYY
  }

}