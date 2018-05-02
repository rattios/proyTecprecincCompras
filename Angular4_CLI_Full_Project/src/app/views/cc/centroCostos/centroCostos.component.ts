import { Component } from '@angular/core';
import {CommonModule} from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import { RutaService } from '../../../services/ruta.service';

import 'rxjs/add/operator/toPromise';

import { DatepickerOptions } from 'ng2-datepicker';
import * as frLocale from 'date-fns/locale/fr';
import * as esLocale from 'date-fns/locale/es';
 

@Component({
  templateUrl: 'centroCostos.component.html'
})
export class centroCostosComponent {

  public prov: any;
  public stock: any;
  public cc: any;
  public productos: any;
  public centroCosto: any='';
  public loading=true;
  public verDatos=false;
  public success=false;
  public fail=false;
  public crear=false;

  public options: DatepickerOptions = {
    displayFormat: 'DD/MM/YYYY',
    barTitleFormat: 'MMMM YYYY',
    dayNamesFormat: 'dd',
    firstCalendarDay: 0,
    locale: esLocale,
  };

  constructor(private http: HttpClient, private ruta: RutaService) {

  }

   ngOnInit(): void {
      this.loading=true;
      this.http.get(this.ruta.get_ruta()+'centro_costos')
           .toPromise()
           .then(
           data => {
             this.prov=data;
               this.cc=this.prov.CentroCostos;
              console.log(this.cc);
              this.productList = this.cc;
              this.filteredItems = this.productList;
              this.init();
              this.loading=false;
            },
           msg => { 
             console.log(msg);
             this.loading=false;
           });
    }

    ver(item){
      if(item==0) {
        this.centroCosto={
          codigo:"",
          descripcion:"",
          habilitado:"",
          desde:new Date(),
          hasta:new Date(),
        };
        this.crear=true;
        console.log(item);
      }else{
        this.centroCosto={
          id:item.id,
          codigo:item.codigo,
          descripcion:item.descripcion,
          habilitado:item.habilitado,
          desde:item.desde,
          hasta:item.hasta,
        };
        if(this.centroCosto.desde==null) {
          this.centroCosto.desde=new Date();
        }
        if(this.centroCosto.hasta==null) {
          this.centroCosto.hasta=new Date();
        }
        this.crear=false;
        console.log(item);
      }
      
      this.verDatos=true;
    }
    volver(){
      this.verDatos=false;
    }
    agregar(item){
      if(!this.checkProductos(item)) {
       this.centroCosto.productos.push(item);
      }
    }
    checkProductos(item){
      var band=false;
      for (var i = 0; i < this.centroCosto.productos.length; i++) {
        if(this.centroCosto.productos[i].id==item.id) {
          band=true;
        }
      }
      return band;
    }
    eliminarProducto(it){
      for (var i = 0; i < this.centroCosto.productos.length; i++) {
        if(this.centroCosto.productos[i].id==it.id) {
          this.centroCosto.productos.splice(i, 1);
        }
      }
    }

    crearcentroCosto(){
      
      var send={
          codigo:this.centroCosto.codigo,
          descripcion:this.centroCosto.descripcion,
          habilitado:this.centroCosto.habilitado,
          desde:this.centroCosto.desde,
          hasta:this.centroCosto.hasta,
        };
      this.http.post(this.ruta.get_ruta()+'centro_costos',send)
           .toPromise()
           .then(
           data => {
            
              console.log(data);
              this.success=true;
              setTimeout(() => {  
                this.success=false;
              }, 4000);

              this.loading=true;
              this.http.get(this.ruta.get_ruta()+'centro_costos')
                 .toPromise()
                 .then(
                 data => {
                   this.prov=data;
                     this.cc=this.prov.CentroCostos;
                    console.log(this.cc);
                    this.productList = this.cc;
                    this.filteredItems = this.productList;
                    this.init();
                    this.loading=false;
                  },
                 msg => { 
                   console.log(msg);
                   this.loading=false;
                 });
             

            },
           msg => { 
             console.log(msg);
             this.fail=true;
              setTimeout(() => {  
                this.fail=false;
              }, 4000);

           });
    }



    editar(){
      
      var send={
          codigo:this.centroCosto.codigo,
          descripcion:this.centroCosto.descripcion,
          habilitado:this.centroCosto.habilitado,
          desde:this.centroCosto.desde,
          hasta:this.centroCosto.hasta,
        };
      console.log(send);
      this.http.put(this.ruta.get_ruta()+'centro_costos/'+this.centroCosto.id,send)
           .toPromise()
           .then(
           data => {
            
              console.log(data);
              this.success=true;
              setTimeout(() => {  
                this.success=false;
              }, 4000);
             
              this.http.get(this.ruta.get_ruta()+'centro_costos')
                 .toPromise()
                 .then(
                 data => {
                   this.prov=data;
                     this.cc=this.prov.CentroCostos;
                    console.log(this.cc);
                    this.productList = this.cc;
                    this.filteredItems = this.productList;
                    this.init();
                    this.loading=false;
                  },
                 msg => { 
                   console.log(msg);
                   this.loading=false;
                 });
            },
           msg => { 
             console.log(msg);
             this.fail=true;
              setTimeout(() => {  
                this.fail=false;
              }, 4000);

           });
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

              if (this.productList[i].razon_social.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].cuit.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].telefono.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].email.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }
            }

            // this.productList.forEach(element => {
            //     if(element.nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0){
            //       this.filteredItems.push(element);
            //    }
            // });
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

    //-------------------------------------------------------------------------------------------------------------------------
   
   filteredItems2 : any;
   productList2 : any;
   pages2 : number = 4;
   pageSize2 : number = 10;
   pageNumber2 : number = 0;
   currentIndex2 : number = 1;
   items2: any;
   pagesIndex2 : Array<number>;
   pageStart2 : number = 1;
   inputName2 : string = '';

   init2(){
         this.currentIndex2 = 1;
         this.pageStart2 = 1;
         this.pages2 = 4;

         this.pageNumber2 = parseInt(""+ (this.filteredItems2.length / this.pageSize2));
         if(this.filteredItems2.length % this.pageSize2 != 0){
            this.pageNumber2 ++;
         }
    
         if(this.pageNumber2  < this.pages2){
               this.pages2 =  this.pageNumber2;
         }
       
         this.refreshItems2();
         console.log("this.pageNumber :  "+this.pageNumber2);
   }
   FilterByName2(){
      this.filteredItems2 = [];
      if(this.inputName2 != ""){
            for (var i = 0; i < this.productList2.length; ++i) {

              if (this.productList2[i].stock==this.inputName2) {
                 this.filteredItems2.push(this.productList2[i]);
              }else if (this.productList2[i].nombre.toUpperCase().indexOf(this.inputName2.toUpperCase())>=0) {
                 this.filteredItems2.push(this.productList2[i]);
              }else if (this.productList2[i].precio==this.inputName2) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList2[i].codigo==this.inputName2) {
                 this.filteredItems2.push(this.productList2[i]);
              }
              
            }

            // this.productList.forEach(element => {
            //     if(element.nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0){
            //       this.filteredItems.push(element);
            //    }
            // });
      }else{
         this.filteredItems2 = this.productList2;
      }
      //console.log(this.filteredItems2);
      this.init2();
   }
   fillArray2(): any{
      var obj2 = new Array();
      for(var index2 = this.pageStart2; index2< this.pageStart2 + this.pages2; index2 ++) {
                  obj2.push(index2);
      }
      return obj2;
   }
 refreshItems2(){
               this.items2 = this.filteredItems2.slice((this.currentIndex2 - 1)*this.pageSize2, (this.currentIndex2) * this.pageSize2);
               this.pagesIndex2 =  this.fillArray();
               console.log(this.items2);
   }
   prevPage2(){
      if(this.currentIndex2>1){
         this.currentIndex2 --;
      } 
      if(this.currentIndex2 < this.pageStart2){
         this.pageStart2 = this.currentIndex2;
      }
      this.refreshItems2();
   }
   nextPage2(){
      if(this.currentIndex2 < this.pageNumber2){
            this.currentIndex2 ++;
      }
      if(this.currentIndex2 >= (this.pageStart2 + this.pages2)){
         this.pageStart2 = this.currentIndex2 - this.pages2 + 1;
      }
 
      this.refreshItems2();
   }
    setPage2(index : number){
         this.currentIndex2 = index;
         this.refreshItems2();
    }
}
