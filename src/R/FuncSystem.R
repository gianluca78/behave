#setwd("/var/www/html/whaamproject/app/data/R/assessment/")
 # data = c(9,10,18,31,17,4,6,8,15,10,1,5,8,3,1,0,0,4,2,3,3,0,5,4,0,1,3,3,0,0,9,9,4,8,3,2,2,4,2,3,3,2,0,3,1,3,2,1,0,0,1,2,1,2,1,2,1,1,1)
 # fase = c("pippo","pippo","pippo","pippo","pippo","pippo","pippo",
 #          "pippo","pippo","pippo","pippo","pippo","pippo","pippo",
 #          "pippo","pippo","pippo","pippo","pippo","pippo","pippo",
 #          "pippo","pippo","pippo","pippo","pippo","pippo","pippo",
 #          "pippo","pippo",
 #          "AnTonio","AnTonio","AnTonio","AnTonio","AnTonio","AnTonio",
 #          "AnTonio","AnTonio","AnTonio","AnTonio","AnTonio","AnTonio",
 #          "AnTonio","AnTonio","AnTonio","AnTonio","AnTonio","AnTonio",
 #          "AnTonio","AnTonio","AnTonio","AnTonio","AnTonio","AnTonio",
 #          "AnTonio","AnTonio","AnTonio","AnTonio","AnTonio")
 # nomifase=c("A","B")

# options(digits.secs = 6)
# print(Sys.time())

# data <- c(7, 7, 7, 9, 5, 8, 1, 0, 0, 1, 0, 21, 21, 12) 
# fase <- c("Baseline", "Baseline","Baseline","Baseline", "Intervention", "Intervention","Intervention",
#           "Intervention","Intervention","Intervention",
#           "Intervention","Intervention","Intervention", "Intervention")
# nomifase <- c("Baseline", "Intervention")


data <- as.numeric(unlist(strsplit(GET$data,",")))
fase <- unlist(strsplit(GET$fase,","))
nomifase <- unlist(strsplit(GET$nomifase,","))



IndicesSystem<-function(){
  # args <- commandArgs(trailingOnly = TRUE) 
  
  require("Kendall")
  require("RJSONIO")
  require("mblm")
  # source(args)
  
  nameTime = "TIME"
  namePhase = "DUMMYPHASE" 
  nameDV = "DV"
  Aphase = 0
  Bphase = 1
  
  # qui trosformo i nomi fase in A e B
  fase[which(fase %in% nomifase[1])]="A"
  fase[which(fase %in% nomifase[2])]="B"
  
  
  data1<-matrix(,ncol = 2,nrow=length(data))
  data1<-as.data.frame(data1)
  data1[,1]<-data
  data1[,2]<-fase

  colnames(data1)<-c("DV","PHASE")
  data1[data1[,"PHASE"] %in% "A","DUMMYPHASE"]=0
  data1[data1[,"PHASE"] %in% "B","DUMMYPHASE"]=1
  data1[,"TIME"]=as.integer(rownames(data1))

  # Parametri Montecarlo
  mase <- function(actual,predicted){
    n <- length(actual)
    denom_i <- rep(0,(n-1))
    for (j in 2:n)
      denom_i[j-1] <- abs(actual[j]-actual[j-1])
    denom <- sum(denom_i)
    
    error_t <- rep(0,n)

   if(sum(denom)==0){
      mase=0
    }else{
    for (j in 1:n)
      error_t[j] <- abs( (actual[j]-predicted[j]) / ( denom/(n-1)) )
    mase <- mean(error_t)
    }    
    # for (j in 1:n)
    #  error_t[j] <- abs( (actual[j]-predicted[j]) / ( denom/(n-1)) )
    # mase <- mean(error_t)
    return(mase)
  }
  Na= length(which(data1$DUMMYPHASE==0))  # N A phase
  Nb= length(which(data1$DUMMYPHASE==1))  # N B phase
  AY=data1[data1$DUMMYPHASE==0,"DV"]
  BY=data1[data1$DUMMYPHASE==1,"DV"]
  AX=data1[data1$DUMMYPHASE==0,"TIME"]
  BX=data1[data1$DUMMYPHASE==1,"TIME"]
  kepA <- Kendall(AX, AY)
  TAUAM=kepA$tau[[1]] # TAU A phase
  kepB <- Kendall(BX, BY)
  TAUBM=kepB$tau[[1]] # TAU B phase
  regi=lm(AY ~ AX)
  R2A=summary(regi)
  RA=sqrt(R2A$r.squared) # R A phase #####!
  if(coefficients(regi)[2]==0 && is.na(RA)){
    RA=1 
  }
  
  maseRA =mase(AY,predict.lm(regi)) # mase A PHASE
  
  regi1A=mblm(AY ~ AX)
  maseTheilSenRA =mase(AY,as.numeric(regi1A$fitted.values)) # maseTheil  A PHASE
  
  regi1B=mblm(BY ~ BX)
  maseTheilSenRB =mase(BY,as.numeric(regi1B$fitted.values)) # maseTheil  B PHASE
  
  
  RBIASA= -0.8457 + 0.1021*Na + 1.3879*RA -2.8350*maseRA
  RBIASA=exp(RBIASA)/(1+exp(RBIASA))
  
  TAUBIASA= -2.76968 + 0.08801*Na + 4.19067*abs(TAUAM) -0.87414*maseTheilSenRA
  TAUBIASA=exp(TAUBIASA)/(1+exp(TAUBIASA))
  
  TAUBIASB= -2.76968 + 0.08801*Nb + 4.19067*abs(TAUBM) -0.87414*maseTheilSenRB
  TAUBIASB=exp(TAUBIASB)/(1+exp(TAUBIASB))
  
  outMonte=NA # 1 prob che bias < .10 
  if(RBIASA >.5){
    outMonte="Allison & Gorman"
  }else if(TAUBIASB>.5){
    
    if(TAUBIASA >.5){
      outMonte="AvsB+trendB-trendA"
    }else{outMonte="AvsB+trendB"}
    
  }else{
    outMonte="AvsB"
  }
  
  
  
#############################
#           TAU
############################  
  
  #Matrix preparation
  dime <- dim(data1)[1]
  coln <- 0
  compa <- matrix(NA, nrow = dime, ncol = dime)

  for (i in 1:dim(data1)[1]) {
    for (l in 1:dim(data1)[1]) {
      compa[i, l] <- data1[dime + 1 - l, "DV"] - data1[i, "DV"]
      coln[l] <- data1[dime + 1 - l, "DV"]
    }
  }
  
  colnames(compa) <- coln
  rownames(compa) <- data1[, "DV"]
  
  NuA <- sum(data1[, namePhase] == Aphase)
  NuB <- sum(data1[, namePhase] == Bphase)

  findindices <- function(mat, rp1, cp2, tri = FALSE) {

    mat <- mat[, rev(seq.int(ncol(mat)))]
    mat[lower.tri(mat)] <- NA
    mat <- mat[, rev(seq_len(ncol(mat)))]
    
    
    if (tri == FALSE) {
      matriAB <- mat[1:NuA, 1:NuB]
      npairsAB <- 0
      nposAB <- 0
      nnegAB <- 0
      
      for (i in 1:dim(matriAB)[1]) {
        for (k in 1:dim(matriAB)[2]) {
          npairsAB <- npairsAB + 1
          if (matriAB[i, k] > 0) {
            nposAB = nposAB + 1
          }
          if (matriAB[i, k] < 0) {
            nnegAB = nnegAB + 1
          }
        }
      }
      
      ro <- rownames(matriAB)
      colo <- colnames(matriAB)
      ze <- rep(0, length(ro))
      un <- rep(1, length(colo))
      sasa <- c(ro, colo)
      ke <- Kendall(c(ro, colo), c(ze, un))
      kefu <- c(ro, colo)
      #	print(kefu) 
      kefu <- Kendall(kefu, 1:length(kefu)) 
      #	print(kefu) 
      varsf <- kefu[[5]]
      pcf <- kefu[[2]]
      
      tt <- c(rep(0, length(ro)), (length(ro) + 1):length(sasa))
      keAu <- Kendall(sasa, tt)
      varsA1 <- keAu[[5]]
      pcA1 <- keAu[[2]]
      
      tt1 <- 1:length(ro)
      tt1 <- c(rev(tt1), (length(ro) + 1):length(sasa))
      keAu1 <- Kendall(sasa, tt1)
      varsA2 <- keAu1[[5]]
      pcA2 <- keAu1[[2]]
      
      S = nposAB - nnegAB
      Tau = S/npairsAB
      
      
      
    }
    else {
      
      if (rp1 == Aphase) {
        matriAB <- mat[1:NuA, (NuB + 1):(NuB + NuA)]
      }
      else {
        matriAB <- mat[(NuA + 1):(NuB + NuA), 1:NuB]
      }
      zero <- 0
      nposAB <- 0
      nnegAB <- 0
      ele <- 0
      
      #print(matriAB)
      
      for (i in 1:dim(matriAB)[1]) {
        for (k in 1:dim(matriAB)[2]) {
          
          
          if ((i == (dim(matriAB)[2] - k + 1)) && is.na(matriAB[i, 
                                                                k]) == FALSE) {
            zero = zero + 1
          }
          if ((matriAB[i, k] > 0) == TRUE && is.na(matriAB[i, 
                                                           k]) == FALSE) {
            nposAB = nposAB + 1
            
          }
          if (matriAB[i, k] < 0 && is.na(matriAB[i, k]) == 
              FALSE) {
            nnegAB = nnegAB + 1
          }
          if (is.na(matriAB[i, k]) == FALSE) {
            ele = ele + 1
          }
          
        }
      }
      
      ro <- rownames(matriAB)
      tre <- 1:length(ro)
      ke <- Kendall(ro, tre)
      
      
      npairsAB = ele - zero
      S = nposAB - nnegAB
      Tau = S/npairsAB
      varsf <- 0
      pcf <- 0
      varsA1 = pcA1 = varsA2 = (pcA2 <- 0)
    }
    
    vars <- (ke[[5]])
    SDs <- sqrt(vars)
    z = S/SDs
    pco <- (ke[[2]])
    pz <- 2 * pnorm(-abs(z))
    
    dd <- c(npairsAB, nposAB, nnegAB, S, Tau, SDs, vars, 
            z, pz, pco, varsf, pcf, varsA1, pcA1, varsA2, pcA2)
    
    return(dd)
  }
  
  
  ABpart <- findindices(compa, Aphase, Bphase, tri = FALSE)
  AApart <- findindices(compa, Aphase, Aphase, tri = TRUE)
  BBpart <- findindices(compa, Bphase, Bphase, tri = TRUE)
  
  vf <- ABpart[11]
  prf <- ABpart[12]
  
  vars_A1 <- ABpart[13]
  pc_A1 <- ABpart[14]
  vars_A2 <- ABpart[15]
  pc_A2 <- ABpart[16]
  
  
  ABpart <- ABpart[1:10]
  AApart <- AApart[1:10]
  BBpart <- BBpart[1:10]
  
  
  PartitionsOfMatrix <- matrix(, nrow = 11, ncol = 3)
  FullMatrix <- matrix(, nrow = 11, ncol = 1)
  TAU_U_Analysis <- matrix(, nrow = 11, ncol = 2)
  
  rownames(PartitionsOfMatrix) <- c("n_pairs", "n_pos", "n_neg", 
                                    "S", "Tau", "SDs", "VARs", "Z", "p_Z_based", "p_exact","r_effect_size")
  colnames(PartitionsOfMatrix) <- c("AvsB", "TrendA", "TrendB")
  
  rownames(FullMatrix) <- c("n_pairs", "n_pos", "n_neg", "S", 
                            "Tau", "SDs", "VARs", "Z", "p_Z_based)", "p_exact","r_effect_size")
  
  rownames(TAU_U_Analysis) <- c("n_pairs", "n_pos", "n_neg", 
                                "S", "Tau", "SDs", "VARs", "Z", "p_Z based", "p_exact","r_effect_size")
  colnames(TAU_U_Analysis) <- c("AvsBTrendB", "AvsBTrendBTrendA")
  
  PartitionsOfMatrix[1:10, 1] <- round(ABpart, 3)
  PartitionsOfMatrix[1:10, 2] <- round(AApart, 3)
  PartitionsOfMatrix[1:10, 3] <- round(BBpart, 3)
  PartitionsOfMatrix[11, ] <- sin(.5*pi*PartitionsOfMatrix["Tau",])
  
  
  FullMatrix[1:4, 1] <- apply(PartitionsOfMatrix[1:4, ], 1, 
                              sum)
  Tauf <- FullMatrix[4, 1]/FullMatrix[1, 1]
  SDf <- sqrt(vf)
  zf = FullMatrix[4, 1]/SDf
  pzf <- 2 * pnorm(-abs(zf))
  FullMatrix[5:10, 1] <- c(Tauf, SDf, vf, zf, pzf, prf)
  FullMatrix <- round(FullMatrix, 3)
  
  zz <- function(k) k[1] + k[3]
  TAU_U_Analysis[1:4, 1] <- apply(PartitionsOfMatrix[1:4, ], 
                                  1, zz)
  Taua1 <- TAU_U_Analysis[4, 1]/TAU_U_Analysis[1, 1]
  
  TAU_U_Analysis[1, 2] <- PartitionsOfMatrix[1, 1] + PartitionsOfMatrix[1,  2] + PartitionsOfMatrix[1, 3]
  TAU_U_Analysis[2, 2] <- PartitionsOfMatrix[2, 1] + PartitionsOfMatrix[2, 3] + PartitionsOfMatrix[3, 2]
  TAU_U_Analysis[3, 2] <- PartitionsOfMatrix[3, 1] + PartitionsOfMatrix[3,   3] + PartitionsOfMatrix[2, 2]
  TAU_U_Analysis[4, 2] <- TAU_U_Analysis[2, 2] - TAU_U_Analysis[3,  2]
  
  TAU_U_Analysis[5:8, 1] <- c(TAU_U_Analysis[4, 1]/TAU_U_Analysis[1, 1], sqrt(vars_A1), vars_A1, TAU_U_Analysis[4, 1]/sqrt(vars_A1))
  TAU_U_Analysis[5:8, 2] <- c(TAU_U_Analysis[4, 2]/TAU_U_Analysis[1,  2], sqrt(vars_A2), vars_A2, TAU_U_Analysis[4, 2]/sqrt(vars_A2))
  TAU_U_Analysis[9:10, 1] <- c(2 * pnorm(-abs(TAU_U_Analysis[8,  1])), pc_A1)
  TAU_U_Analysis[9:10, 2] <- c(2 * pnorm(-abs(TAU_U_Analysis[8, 2])), pc_A2)
  TAU_U_Analysis <- round(TAU_U_Analysis, 3)
  
  mat1 <- compa[, rev(seq.int(ncol(compa)))]
  mat1[lower.tri(mat1)] <- NA
  mat1 <- mat1[, rev(seq_len(ncol(compa)))]
  #print(mat1)
  
  FullMatrix[11, ] <- sin(.5*pi*FullMatrix["Tau",])
  TAU_U_Analysis[11, ] <- sin(.5*pi*TAU_U_Analysis["Tau",])
  
  ###############################################
  #                  Allison & Gorman           #
  ###############################################
  slopes=TRUE
  nameTime="TIME"
  namePhase="DUMMYPHASE"
  nameDV="DV"
  nameint="TIMEna"
  miny= "-inf"
  maxy= "+inf"
  Aphase=0
  Bphase=1
  
  minB<-min(data1[data1[,"DUMMYPHASE"]==Bphase,"TIME"])
  
  na=0
  for(i in 1:dim(data1)[1]){
    
    if(  (data1[i,"TIME"])< minB && data1[i,"DUMMYPHASE"]==Aphase){
      
      na=na+1
      
    }
  }
  
  fTimeNa<-function(Timeserie){tna<- Timeserie-na
  return(tna)}
  TIMEna<-sapply(data1[,"TIME"],fTimeNa)
  data1<-cbind(data1,TIMEna)
  
  
  data2<-data1[data1[,"DUMMYPHASE"] == 0, ]
  
  
  DV0=data2[,"DV"]
  TIME0=data2[,"TIME"]
  TIMEna0<-data2[,"TIMEna"]
  
  DV=data1[,"DV"]
  TIME=data1[,"TIME"]
  TIMEna<-data1[,"TIMEna"]
  DUMMYPHASE<-data1[,"DUMMYPHASE"]
  
  regr1 <- lm(DV0 ~ TIME0) #Step1
  coe <- coefficients(regr1)
  
  
  pred<-rep(NA,dim(data1)[1])
  diffe<-rep(NA,dim(data1)[1])
  int<-rep(NA,dim(data1)[1])
  data1<-cbind(data1,pred,diffe,int)
  
  for (i in 1:dim(data1)[1]) {
    
    
    data1[i, "pred"] <-  coe[1] + coe[2] * data1[i, "TIME"] # steps 2 3 4
    
    if(miny!= "-inf"){
      if(data1[i, "pred"] < miny){data1[i, "pred"]=miny}}
    if(maxy!= "+inf"){
      if(data1[i, "pred"] > maxy){data1[i, "pred"]=maxy}}
    
    data1[i, "diffe"] <- data1[i, "DV"] - data1[i, "pred"] # steps 2 3 4
    data1[i, "diffe"]<-round(data1[i, "diffe"],11)#tentativo di eliminare .machine eps (fare meglio)
    
    data1[i, "int"]<-data1[i, "DUMMYPHASE"]*data1[i, "TIMEna"]	# steps 5
  }
  
  pred=data1[,"pred"]
  diffe=data1[,"diffe"]
  int=data1[,"int"]
  
  rsdum<-lm(diffe ~ DUMMYPHASE)# steps 5
  rsint<-lm(diffe ~ int)# steps 5
  produ<-coefficients(rsdum)[[2]]*coefficients(rsint)[[2]]# steps 5
  
  if (slopes==TRUE) {
    method="ALLISONMT";method1="levels and slopes"
    if(produ>=0){
      
      regrAL <- lm(diffe ~ DUMMYPHASE + DUMMYPHASE:TIMEna)# step6
      
      
      redata<-(summary(regrAL))# step7
      
      Fdata<-redata[[10]]# step7
      
      denF<-Fdata[3]# step7
      numF<-Fdata[2]# step7
      valF<-Fdata[1]# step7
      dConNum<- 2*((valF*numF/denF)^(.5))# step8
      #dSenzaNum<- 2*((valF/denF)^(.5))# step8
      
      if(coefficients(rsdum)[[2]]>0){# step9
        dConNum=dConNum # step9
        #dSenzaNum=dSenzaNum # step9
      }else{ # step9
        dConNum=-dConNum # step9
        #dSenzaNum=-dSenzaNum # step9
      } # step9
      
      #print(paste("Method Used: ALLISON_MT, produ = ",produ))
      
    }else{
      
      slopes=FALSE
      method="ALLISONM"
      
    }
    
  }
  
  if (slopes==FALSE) {
    # nuovo
    method="ALLISONMT";method1="levels only"
    
    #print(paste("Method Used: ALLISON_M, produ = ",produ))
    
    regrAL <- lm(diffe ~ DUMMYPHASE)
    
    redata<-(summary(regrAL))
    
    Fdata<-redata[[10]]
    
    denF<-Fdata[3]
    numF<-Fdata[2]
    valF<-Fdata[1]
    dConNum<- 2*((valF*numF/denF)^(.5))
    #dSenzaNum<- 2*((valF/denF)^(.5))
    
    if(coefficients(regrAL)[[2]]>0){
      dConNum=dConNum 
      #dSenzaNum=dSenzaNum 
    }else{ 
      dConNum=-dConNum 
      #dSenzaNum=-dSenzaNum 
    } 
    
    
  }
  
  
  
  if(sum(diffe[is.na(diffe)==FALSE]==rep(0,length(diffe[is.na(diffe)==FALSE])))==length(diffe[is.na(diffe)==FALSE])){dConNum=0}
  
  if(abs(dConNum)>=134217728){ 
    R<-sign(dConNum)*1}else{
      R<- dConNum/((4+dConNum^2)^.5)}
  
  
  ################################################
  cmd = list(outMonte=outMonte, PartitionsOfMatrix = PartitionsOfMatrix, FullMatrix = FullMatrix,
             TAU_U_Analysis = TAU_U_Analysis,
             regression=redata,database=data1,regcoefficients=coe,
             method=method,cohen_d_ConNum=dConNum,product=produ,R=R,miny=miny,maxy=maxy,smethod=method1
             )#, matri = mat1)
  cmd=toJSON(cmd, collapse=" ", )
  cmd=gsub("\n", "", cmd)
  return(cmd)
  
  
}

writeLines(IndicesSystem())
# print(Sys.time())
