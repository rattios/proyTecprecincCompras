import { Component } from '@angular/core';
import {CommonModule} from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { RutaService } from '../../services/ruta.service';

@Component({
  templateUrl: 'proveedores.component.html'
})
export class ProveedoresComponent {
  public prov: any;
  public proveedores: any;
  public productos: any;
  public proveedor: any='';
  constructor(private http: HttpClient, private ruta: RutaService) {

  }

   ngOnInit(): void {

      this.http.get(this.ruta.get_ruta()+'proveedores')
           .toPromise()
           .then(
           data => {
             this.prov=data;
           	  this.proveedores=this.prov.proveedores;
              console.log(this.proveedores);
              
            },
           msg => { 
             console.log(msg);
           });
       this.http.get('http://vivomedia.com.ar/Tecprecinc/productos.json')
           .toPromise()
           .then(
           data => {
           	  this.productos=data;
              console.log(this.productos);
              
            },
           msg => { 
             console.log(msg);
           });
    }

    ver(item){
    	this.proveedor=item.razonSocial;
    }
    setProductos(){
      console.log('asdasd');
    }
}
