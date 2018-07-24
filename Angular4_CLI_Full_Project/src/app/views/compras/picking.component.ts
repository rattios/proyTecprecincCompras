import { Component, OnInit, Input } from '@angular/core';
import {CommonModule, NgClass} from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { RutaService } from '../../services/ruta.service';

@Component({
  selector: 'app-picking',
  templateUrl: 'picking.component.html'
})
export class pickingComponent {
  public prov: any;
  public pedidos: any;
  public productos: any;
  public proveedor: any='';
  public showP1=true;
  public showP2=true;
  public loading=true;
  public departamentos:any;
  public empleados:any;
  public empleado_id:any;
  @Input() producto:any;
  @Input() informacion:any;

  constructor(private http: HttpClient, private ruta: RutaService) {

  }

   ngOnInit(): void {
       this.loading=false;
      console.log(this.producto);
     
    }

    

    picking(item,tipo){
      console.log(item);
        var send={
          cantidad:1
        }
        this.http.post(this.ruta.get_ruta()+'pedidos/picking',send)
             .toPromise()
             .then(
             data => {
               console.log(data);
             },msg => { 
               console.log(msg);
             });
    }  
}
