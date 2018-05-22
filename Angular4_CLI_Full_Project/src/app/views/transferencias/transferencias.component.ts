import { Component } from '@angular/core';
import {CommonModule} from '@angular/common';
import { HttpClient, HttpParams, HttpHeaders } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { RutaService } from '../../services/ruta.service';

@Component({
  templateUrl: 'transferencias.component.html'
})
export class transferenciasComponent {
  public prov: any;
  public centroCostos: any;
  public idCentroCostos: any=43;
  public contratos:any=[];
  public idContrato:any=1;
  public stock: any;
  public productos: any;
  public departamentos: any;
  public proveedor: any='';
  public departamento=localStorage.getItem('tecprecinc_nombre');
  public template:'http://localhost/template.gif';
  public loading=true;
  public success=false;
  public fail=false;
  public seTransfirio=true;
  public cantidad=0;
  public producto:any={
    nombre:''
  };
  constructor(private http: HttpClient, private ruta: RutaService) {

  }

   ngOnInit(): void {
      this.loading=true;
      let headers = new HttpHeaders();
      headers = headers.append("Authorization", "Bearer " + localStorage.getItem('tecprecinc_token'));
      //headers = headers.append("Content-Type", "application/json");
    
      this.http.get(this.ruta.get_ruta()+'stock/transferencias?token='+localStorage.getItem('tecprecinc_token'), {
            headers: headers
        }).toPromise()
           .then(
           data => {
             this.prov=data;
           	  this.stock=this.prov.productos;
              this.departamentos=this.prov.departamentos;
              console.log(this.stock);
              console.log(this.departamentos);
              this.productList = this.stock;
              this.filteredItems = this.productList;
              this.init();
              this.loading=false;
              
              
            },
           msg => { 
             console.log(msg);
              this.loading=false;
           });
    }

    setProductos(item){
      console.log(item);
      this.cantidad=0;
      this.producto=item;
    }

    checkCantidad(cantidad){
      console.log(cantidad);
      if(this.cantidad>=0) {
        if(this.producto.stock<cantidad) {
          alert('La cantidad no puede ser mayor a la del stock.');
          this.cantidad=0;
        }
      }else{
        this.cantidad=0;
      }
    }
    
    enviar(depar){
      if(this.cantidad>0) {
        console.log(depar);
        var enviar = {
          cantidad_transf:this.cantidad,
          stock_id:this.producto.id,
          departamento_id:depar
        }
        console.log(enviar);

        setTimeout(() => {
          this.http.post(this.ruta.get_ruta()+'transferencias',enviar)
           .toPromise()
           .then(
           data => {
             console.log(data);
              this.success=true;
              setTimeout(() => {  
                this.success=false;
              }, 4000);
              this.seTransfirio=false;
            },
           msg => { 
             console.log(msg);
             this.fail=true;
              setTimeout(() => {  
                this.fail=false;
              }, 4000);
             
           });
        }, 1000);
      }else{
        alert('La cantidad debe ser mayor a cero.');
      }
        
    }
    

    //-------------------------------------------------------------------------------------------------------------------------
   
   filteredItems : any;
   productList : any;
   pages : number = 4;
   pageSize : number = 10;
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

              if (this.productList[i].stock==this.inputName) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].precio==this.inputName) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].codigo==this.inputName) {
                 this.filteredItems.push(this.productList[i]);
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
