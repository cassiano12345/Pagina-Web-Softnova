-- Grupos de Acessos =============================================================================
create table DASH_GRUPOS
(CODEMP           NUMBER(3) NOT NULL,
 IDGRUPO          NUMBER(3) NOT NULL,
 NOME             VARCHAR2(50),
 DESCRICAO        VARCHAR2(50),
 VENDAS           VARCHAR2(3),
 COMPRAS          VARCHAR2(3),
 STOCKS           VARCHAR2(3),
 CCORRENTES       VARCHAR2(3),
 FORNECEDORES     VARCHAR2(3));

DROP INDEX IND_DASH_GRUPOS;
CREATE UNIQUE INDEX IND_DASH_GRUPOS ON DASH_GRUPOS (CODEMP, IDGRUPO) TABLESPACE USER_INDEX;


-- Lojas =========================================================================================
create table DASH_LOJAS
(CODEMP       NUMBER(3)       NOT NULL,
 ID           NUMBER(3)       NOT NULL,
 NOME         VARCHAR2(300)   NOT NULL,
 ARMAZEM      VARCHAR2(12)    NOT NULL,
 LOCALIZACAO  VARCHAR2(50),
 GERENTE      VARCHAR2(50),
 IMAGEM       VARCHAR2(1000));

DROP INDEX IND_DASH_LOJAS; 
CREATE UNIQUE INDEX IND_DASH_LOJAS ON DASH_LOJAS (CODEMP, ID) TABLESPACE USER_INDEX;


-- Acessos por Grupo/Loja ==========================================================================
create table DASH_ACESSOS
(CODEMP           NUMBER(3)       NOT NULL,
 IDGRUPO          NUMBER(3)       NOT NULL,
 IDLOJA        	  number(3)       NOT NULL);

DROP INDEX IND_DASH_ACESSOS;
CREATE UNIQUE INDEX IND_DASH_ACESSOS ON DASH_ACESSOS (CODEMP, IDGRUPO, IDLOJA) TABLESPACE USER_INDEX; 
 
 
-- SubAcessos =====================================================================================
Create table dash_subacessos
( CODEMP  			number(3)   NOT NULL,
  ID      			number(3)   NOT NULL,
  IDGRUPO 			number(3)   NOT NULL,
  MENU    			varchar2(50) NOT NULL,
  SUBMENU 			varchar2(50) NOT NULL,
  ORDEM   			number(3));

DROP INDEX IND_DASH_SUBACESSOS;
CREATE UNIQUE INDEX IND_DASH_SUBACESSOS ON DASH_SUBACESSOS (CODEMP, ID, IDGRUPO, MENU, SUBMENU, ORDEM) TABLESPACE USER_INDEX; 


-- =================================================================================================

alter table Soft_Util add (Grupo number(3));
alter table Soft_Util add (PasswordDB varchar2(100));

insert into Dash_SubAcessos (id,codemp,idgrupo,menu,submenu,ordem) values (1,1,1,'VENDAS','Diarias',1);
insert into Dash_SubAcessos (id,codemp,idgrupo,menu,submenu,ordem) values (1,1,1,'VENDAS','Mensais',2);
insert into Dash_SubAcessos (id,codemp,idgrupo,menu,submenu,ordem) values (1,1,1,'ARTIGOS','Ficha de Artigos',1);
insert into Dash_SubAcessos (id,codemp,idgrupo,menu,submenu,ordem) values (1,1,1,'ARTIGOS','Stocks',2);

-- URL==============================================================================================
alter table soft_confapp add(CAMINHODASH  VARCHAR2(1000));

commit;

