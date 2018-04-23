import { Component } from '@angular/core';
import {CommonModule} from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { RutaService } from '../../../services/ruta.service';

@Component({
  templateUrl: 'transferenciasPura.component.html'
})
export class transferenciasPuraComponent {
  public prov: any;
  public transferencias: any=[];
  public productos: any;
  public informacion: any;
  public proveedor: any='';
  public loading=true;
  public verInfo=false;
  constructor(private http: HttpClient, private ruta: RutaService) {

  }

   ngOnInit(): void {

      this.http.get(this.ruta.get_ruta()+'pedidos/departamento/'+localStorage.getItem('tecprecinc_usuario_id'))
           .toPromise()
           .then(
           data => {
             console.log(data);
             this.prov=data;
              
              this.transferencias=this.prov.transferencias;
              
              this.loading=false;
            },
           msg => { 
             console.log(msg);
             this.loading=false;
             alert('Ha ocurrido un error!');
           });
    }

    reset(){
      this.loading=true;

      this.http.get(this.ruta.get_ruta()+'pedidos')
           .toPromise()
           .then(
           data => {
             this.prov=data;
              this.transferencias=this.prov.transferencias;

              this.loading=false;
            },
           msg => { 
             console.log(msg);
             this.loading=false;
             alert('Ha ocurrido un error!');
           });
    }
}
