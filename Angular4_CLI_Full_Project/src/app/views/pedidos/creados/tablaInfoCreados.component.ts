import { Component, OnInit, Input } from '@angular/core';
import {CommonModule} from '@angular/common';
import { HttpClient, HttpParams,HttpHeaders  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { RutaService } from '../../../services/ruta.service';
import { creadosComponent } from './creados.component';
import { saveAs } from 'file-saver/FileSaver';

@Component({
  selector: 'app-tabla-info-creados',
  templateUrl: 'tablaInfoCreados.component.html'
})
export class tablaInfoCreadosComponent {

  public verInfo=false;
  public loading=false;
  public prov: any;
  public centroCostos: any;
  public stock: any;
  
  @Input() informacion:any;

  constructor(private http: HttpClient, private ruta: RutaService, private parent: creadosComponent) {

  }

   ngOnInit(): void {
      console.log(this.informacion);
      if(this.informacion!=undefined) {
       //this.productList = this.informacion;
       //this.filteredItems = this.productList;
       //this.init();
      }

      let headers = new HttpHeaders();
      headers = headers.append("Authorization", "Bearer " + localStorage.getItem('tecprecinc_token'));

      this.http.get(this.ruta.get_ruta()+'stock/permitido?token='+localStorage.getItem('tecprecinc_token'), {
            headers: headers
        }).toPromise()
           .then(
           data => {
             this.prov=data;
               this.stock=this.prov.productos;
              console.log(this.stock);
              this.centroCostos=this.prov.centrocostos;
              console.log(this.centroCostos);
              this.productList = this.stock;
              this.filteredItems = this.productList;
              this.init();
              this.loading=false;
              setTimeout(() => {
                  this.cc2(43);
              }, 1000);
              
            },
           msg => { 
             console.log(msg);
              this.loading=false;
           });   
    }
    aceptarSolicitud(id){
      console.log(id);
      var aceptar={
        aprobar:1
      }
      this.http.put(this.ruta.get_ruta()+'pedidos/'+this.informacion.id,aceptar)
         .toPromise()
         .then(
         data => {
           console.log(data);
           this.parent.reset();
          },
         msg => { 
           console.log(msg);
           alert('Ha ocurrido un error!');
         }); 

    }
    finalizarSolicitud(id){
      console.log(id);
      var aceptar={
        estado:2
      }
      this.http.put(this.ruta.get_ruta()+'pedidos/'+this.informacion.id,aceptar)
           .toPromise()
           .then(
           data => {
             console.log(data);
             this.parent.reset();
            },
           msg => { 
             console.log(msg);
             alert('Ha ocurrido un error!');
           });     
    }
    cancelarSolicitud(id){
      console.log(id);
      var aceptar={
        estado:4
      }
      this.http.put(this.ruta.get_ruta()+'pedidos/'+this.informacion.id,aceptar)
           .toPromise()
           .then(
           data => {
             console.log(data);
             this.parent.reset();
            },
           msg => { 
             console.log(msg);
             alert('Ha ocurrido un error!');
           });     
    }

    public productosSeleccionados:any;
    public idCentroCostos:any;
    public idContrato:any;
    public idinformacion:any;
    public observaciones:any;
    public user:any;
    ver(item){

      console.log(item);
      this.idinformacion=item.id;
      this.productosSeleccionados =item.solicitud;
      for (var i = 0; i < this.productosSeleccionados.length; i++) {
        this.productosSeleccionados[i].cantidad=this.productosSeleccionados[i].pivot.cantidad;
      }
      this.observaciones= item.observaciones;
      this.idCentroCostos =item.centro_costos_id;
      this.idContrato =item.contrato_id;
      this.user = item.usuario.nombre;
      
      this.verInfo=true;
    }
    volver(){
      this.verInfo=false;
    }
    public success:any=false;
    public fail:any=false;
    
    enviar(){
      if(this.productosSeleccionados.length>0) {
        var enviar = {
          usuario_id: localStorage.getItem('tecprecinc_usuario_id'),
          solicitud: JSON.stringify(this.productosSeleccionados),
          solicitud2: this.productosSeleccionados,
          centro_costos_id:this.idCentroCostos,
          contrato_id:this.idContrato,
          estado: 0,
          aprobar:0,
          observaciones:this.observaciones,
          departamento_id:localStorage.getItem('tecprecinc_departamento_id'),
        }
        console.log(enviar);

        setTimeout(() => {
          this.http.post(this.ruta.get_ruta()+'pedidos',enviar)
           .toPromise()
           .then(
           data => {
             console.log(data);
              
              this.vaciar();
              this.success=true;
              setTimeout(() => {  
                this.success=false;
              }, 4000);

            },
           msg => { 
             console.log(msg);
             this.fail=true;
              setTimeout(() => {  
                this.fail=false;
              }, 4000);
             
           });
        }, 1000);
       } 
    }

    enviaryguardar(){

      if(this.productosSeleccionados.length>0) {
        
        console.log(this.idinformacion);
        var enviar={
          observaciones:this.observaciones,
          centro_costos_id:this.idCentroCostos,
          solicitud:JSON.stringify(this.productosSeleccionados),
          estado: 0,
        }
        console.log(enviar);
        this.http.put(this.ruta.get_ruta()+'editar_observacion/'+this.idinformacion,enviar)
           .toPromise()
           .then(
           data => {
             console.log(data);
             alert('¡Se envió el pedido y guardaron los cambios con éxito!');
              this.success=true;
              setTimeout(() => {  
                this.success=false;
              }, 4000);
              this.vaciar();

            },
           msg => { 
             console.log(msg);
             alert('¡Falló al guardar los cambios!');
             this.fail=true;
              setTimeout(() => {  
                this.fail=false;
              }, 4000);
             
           });
      }
    }

    guardar(){

      console.log(this.idinformacion);
      console.log(this.informacion);

      var enviar={
        observaciones:this.observaciones,
        centro_costos_id:this.idCentroCostos,
        solicitud:JSON.stringify(this.productosSeleccionados),
        estado: 5
      }
      console.log(enviar);
      this.http.put(this.ruta.get_ruta()+'editar_observacion/'+this.idinformacion,enviar)
           .toPromise()
           .then(
           data => {
             console.log(data);
             alert('¡Se guardaron los cambios con éxito!');
              this.success=true;
              setTimeout(() => {  
                this.success=false;
              }, 4000);
              this.vaciar();
            },
           msg => { 
             console.log(msg);
             alert('¡Falló al guardar los cambios!');
             this.fail=true;
              setTimeout(() => {  
                this.fail=false;
              }, 4000);
             
           });
    }
    
    vaciar(){
      this.productosSeleccionados=[];
      this.observaciones='';
    }

    setProductos(item){
      if(!this.checkProductos(item)) {
        item.cantidad=1;
        item.producto_id=item.id;
        item.centro_costos_id=43;
        item.pivot={
          aprobado:0,
          cancelado:0,
          cantidad:1,
          cantidad_entregada:null,
          centro_costos_id:43,
          devuelto:0,
          entregado:0,
          f_entrega:null,
          nombre_centro_costo:"Sin centro costos",
          observacion:null,
          observaciones:null,
          pedido_id:this.idinformacion,
          pendiente:null,
          stock_id:item.id,
          tipo_entrega:null
        };
        this.productosSeleccionados.push(item);
      }
      
      console.log(this.productosSeleccionados);
    }
    checkProductos(item){
      var band=false;
      for (var i = 0; i < this.productosSeleccionados.length; i++) {
        if(this.productosSeleccionados[i].id==item.id) {
          band=true;
        }
      }
      return band;
    }
    delProductos(item){
      for (var i = 0; i < this.productosSeleccionados.length; i++) {
        if(this.productosSeleccionados[i].id==item.id) {
          this.productosSeleccionados.splice(i, 1);
        }
      }
    }
    public contratos:any;
    cc2(centro_costo_id) {
      console.log(centro_costo_id);
      this.contratos=[];
      
      for (var i = 0; i < this.centroCostos.length; i++) {
        for (var j = 0; j < this.centroCostos[i].contratos.length; j++) {
          console.log(i+'='+j);
          console.log(this.centroCostos[i].contratos[j].centro_costos_id+'='+centro_costo_id);
          if(this.centroCostos[i].contratos[j].centro_costos_id==centro_costo_id) {
            this.contratos.push(this.centroCostos[i].contratos[j]);
          }
        }
      }
    }

    public saveFile(){
    console.log('exportar');
    var blob = new Blob([document.getElementById('exportable').innerHTML], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, 'Solicitudes por aprobar.xls');
         //FileSaver.saveAs(blob, CONFIG.ECOMMERCE_NOMBRE+".xls");
    }

     //-------------------------------------------------------------------------------------------------------------------------
   
   filteredItems : any;
   productList : any;
   pages : number = 4;
   pageSize : number = 100;
   pageNumber : number = 0;
   currentIndex : number = 1;
   items: any;
   pagesIndex : Array<number>;
   pageStart : number = 1;
   inputName : string = '';

   init(){
         this.currentIndex = 1;
         this.pageStart = 1;
         this.pages = 4;

         this.pageNumber = parseInt(""+ (this.filteredItems.length / this.pageSize));
         if(this.filteredItems.length % this.pageSize != 0){
            this.pageNumber ++;
         }
    
         if(this.pageNumber  < this.pages){
               this.pages =  this.pageNumber;
         }
       
         this.refreshItems();
         console.log("this.pageNumber :  "+this.pageNumber);
   }
   FilterByName(){
      this.filteredItems = [];
      if(this.inputName != ""){
            for (var i = 0; i < this.productList.length; ++i) {

              if (this.productList[i].id==this.inputName) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].usuario.nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].usuario.departamento.nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].created_at.indexOf(this.inputName)>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if(this.productList[i].observaciones!='' && this.productList[i].observaciones!=null) {
                if (this.productList[i].observaciones.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
                }
              }
            }
      }else{
         this.filteredItems = this.productList;
      }
      console.log(this.filteredItems);
      this.init();
   }
   fillArray(): any{
      var obj = new Array();
      for(var index = this.pageStart; index< this.pageStart + this.pages; index ++) {
                  obj.push(index);
      }
      return obj;
   }
 refreshItems(){
               this.items = this.filteredItems.slice((this.currentIndex - 1)*this.pageSize, (this.currentIndex) * this.pageSize);
               this.pagesIndex =  this.fillArray();
   }
   prevPage(){
      if(this.currentIndex>1){
         this.currentIndex --;
      } 
      if(this.currentIndex < this.pageStart){
         this.pageStart = this.currentIndex;
      }
      this.refreshItems();
   }
   nextPage(){
      if(this.currentIndex < this.pageNumber){
            this.currentIndex ++;
      }
      if(this.currentIndex >= (this.pageStart + this.pages)){
         this.pageStart = this.currentIndex - this.pages + 1;
      }
 
      this.refreshItems();
   }
    setPage(index : number){
         this.currentIndex = index;
         this.refreshItems();
    }
}
