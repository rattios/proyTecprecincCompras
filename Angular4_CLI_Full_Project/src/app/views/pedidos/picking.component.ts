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

  public departamentos:any;
  @Input() producto:any;
  @Input() informacion:any;

  constructor(private http: HttpClient, private ruta: RutaService) {

  }

   ngOnInit(): void {
      console.log(this.producto);
      this.producto.bstock=true;
      this.producto.bstock2=true;
      //console.log(this.informacion);
    }

    picking(item,tipo){
      console.log(item);
      if(tipo==1) {
        item.almacen='principal';
        console.log(item);
        var send = {
          picking: JSON.stringify(item)
        }

        this.http.post(this.ruta.get_ruta()+'pedidos/picking',send)
             .toPromise()
             .then(
             data => {
               console.log(data);
               var rec:any;
               rec=data;
               console.log(this.producto);
               console.log(rec);
               console.log(rec.picking);
               for (var i = 0; i < this.informacion.solicitud.length; i++) {
                 if(this.informacion.solicitud[i].id==rec.picking.id) {
                   this.informacion.solicitud[i]=rec.picking;
                 }
               }
               this.producto.stock=this.producto.stock-item.pivot.cantidad;
               this.showP1=false;
               this.showP2=false;
               alert('éxito');
              },
             msg => { 
               console.log(msg);
               alert('Error: '+JSON.stringify(msg));
             });
      }else if(tipo==2){
        item.almacen='secundario';
        console.log(item);
        var send = {
          picking: JSON.stringify(item)
        }

        this.http.post(this.ruta.get_ruta()+'pedidos/picking',send)
             .toPromise()
             .then(
             data => {
               console.log(data);
               var rec:any;
               rec=data;
               console.log(this.producto);
               console.log(rec);
               console.log(rec.picking);
               for (var i = 0; i < this.informacion.solicitud.length; i++) {
                 if(this.informacion.solicitud[i].id==rec.picking.id) {
                   this.informacion.solicitud[i]=rec.picking;
                 }
               }
               this.producto.stock2=this.producto.stock-item.pivot.cantidad;
               this.showP1=false;
               this.showP2=false;
               alert('éxito');
              },
             msg => { 
               console.log(msg);
               alert('Error: '+JSON.stringify(msg));
             });
      }
    }  
}
