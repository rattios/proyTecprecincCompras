import { NgModule } from '@angular/core';
import {CommonModule} from '@angular/common';
import { ChartsModule } from 'ng2-charts/ng2-charts';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';

import { ProveedoresComponent } from './proveedores.component';
import { ProveedoresRoutingModule } from './proveedores-routing.module';

@NgModule({
  imports: [
    ProveedoresRoutingModule,
    ChartsModule,
    HttpClientModule,
    CommonModule,
    FormsModule
  ],
  declarations: [ ProveedoresComponent ]
})
export class ProveedoresModule { }
