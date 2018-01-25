import { NgModule } from '@angular/core';
import {CommonModule} from '@angular/common';
import { ChartsModule } from 'ng2-charts/ng2-charts';
import { HttpClientModule } from '@angular/common/http';

import { CategoriasComponent } from './categorias.component';
import { CategoriasRoutingModule } from './categorias-routing.module';

@NgModule({
  imports: [
    CategoriasRoutingModule,
    ChartsModule,
    HttpClientModule,
    CommonModule
  ],
  declarations: [ CategoriasComponent ]
})
export class CategoriasModule { }
