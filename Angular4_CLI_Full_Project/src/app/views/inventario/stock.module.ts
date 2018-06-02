import { NgModule } from '@angular/core';
import {CommonModule} from '@angular/common';
import { ChartsModule } from 'ng2-charts/ng2-charts';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { TabsModule } from 'ngx-bootstrap/tabs';

import { stockComponent } from './stock.component';
import { stock2Component } from './stock2.component';
import { stockDepartamentoComponent } from './stockDepartamentos.component';
import { stockRoutingModule } from './stock-routing.module';
import { infoComponent } from './info.component';
import { info2Component } from './info2.component';

@NgModule({
  imports: [
    stockRoutingModule,
    ChartsModule,
    HttpClientModule,
    CommonModule,
    FormsModule,
    TabsModule.forRoot(),
  ],
  declarations: [ 
  	stockComponent,
    stock2Component,
    infoComponent,
    info2Component,
    stockDepartamentoComponent
   ]
})
export class stockModule { }
