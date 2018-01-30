import { Component } from '@angular/core';
import {CommonModule} from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { RutaService } from '../../services/ruta.service';

@Component({
  templateUrl: 'stock.component.html'
})
export class stockComponent {
  public prov: any;
  public stock: any;
  public productos: any;
  public proveedor: any='';
  constructor(private http: HttpClient, private ruta: RutaService) {

  }

   ngOnInit(): void {

      this.http.get(this.ruta.get_ruta()+'stock')
           .toPromise()
           .then(
           data => {
             this.prov=data;
           	  this.stock=this.prov.productos;
              console.log(this.stock);
              
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
