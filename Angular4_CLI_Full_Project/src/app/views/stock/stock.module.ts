import { NgModule } from '@angular/core';
import {CommonModule} from '@angular/common';
import { ChartsModule } from 'ng2-charts/ng2-charts';
import { HttpClientModule } from '@angular/common/http';

import { stockComponent } from './stock.component';
import { stockRoutingModule } from './stock-routing.module';

@NgModule({
  imports: [
    stockRoutingModule,
    ChartsModule,
    HttpClientModule,
    CommonModule
  ],
  declarations: [ stockComponent ]
})
export class stockModule { }
