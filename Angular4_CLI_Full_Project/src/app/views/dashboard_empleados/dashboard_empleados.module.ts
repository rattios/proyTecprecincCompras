import { NgModule } from '@angular/core';
import { ChartsModule } from 'ng2-charts/ng2-charts';
import { BsDropdownModule } from 'ngx-bootstrap/dropdown';
import { CommonModule} from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';

import { Dashboard_empleadosComponent } from './dashboard_empleados.component';
import { Dashboard_empleadosRoutingModule } from './dashboard_empleados-routing.module';

@NgModule({
  imports: [
    Dashboard_empleadosRoutingModule,
    ChartsModule,
    BsDropdownModule,
    HttpClientModule,
    CommonModule,
    FormsModule,
  ],
  declarations: [ Dashboard_empleadosComponent ]
})
export class Dashboard_empleadosModule { }
