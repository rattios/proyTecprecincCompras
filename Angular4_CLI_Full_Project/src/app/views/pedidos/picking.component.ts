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
      this.producto.bstock=true;
      this.producto.bstock2=true;
      this.http.get(this.ruta.get_ruta()+'usuarios?rol='+10+"&departamento_id="+this.producto.departamento.id)
           .toPromise()
           .then(
           data => {
              this.prov=data;
              this.empleados=this.prov;
              for (var i = 0; i < this.empleados.length; i++) {
                if(this.empleados[i].rol==0) {
                  this.empleados[i].nombreRol='ADMIN';
                }else if(this.empleados[i].rol==1) {
                  this.empleados[i].nombreRol='SUPERVISOR';
                }else if(this.empleados[i].rol==2) {
                  this.empleados[i].nombreRol='EMPLEADO';
                }
              }
              console.log(this.empleados);
              this.empleado_id=this.producto.usuario.id;
            },
           msg => { 
             console.log(msg);
             this.loading=false;
           });
      //console.log(this.informacion);
    }

    traxas(stock_id,cantidad,d_emisor_id,d_receptor_id,u_emisor_id,u_receptor_id,operacion_id,tipo){
      var sendTraza={
       stock_id:stock_id,
       cantidad:cantidad,
       d_emisor_id:d_emisor_id,
       d_receptor_id:d_receptor_id,
       u_emisor_id:u_emisor_id,
       u_receptor_id:u_receptor_id,
       operacion_id:operacion_id,
       tipo:tipo
      }
      console.log(sendTraza);
      this.http.post(this.ruta.get_ruta()+'trazas',sendTraza)
       .toPromise()
       .then(
       data => {
         console.log(data);
         console.log('éxito al registrar la traza');
        },
       msg => { 
         console.log(msg);
         console.log('Error: '+JSON.stringify(msg));
      });
    }

    picking(item,tipo){
      console.log(item);
      if(tipo==1) {
        item.almacen='principal';
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
               this.traxas(item.pivot.stock_id,item.pivot.cantidad,100,item.departamento.id,localStorage.getItem('tecprecinc_usuario_id'),this.empleado_id,item.pivot.pedido_id,'Picking');
              },msg => { 
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
               this.traxas(item.pivot.stock_id,item.pivot.cantidad,101,item.departamento.id,localStorage.getItem('tecprecinc_usuario_id'),this.empleado_id,item.pivot.pedido_id,'Picking');
              },
             msg => { 
               console.log(msg);
               alert('Error: '+JSON.stringify(msg));
             });
      }
    }  
}
