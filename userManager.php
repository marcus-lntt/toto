<?php
session_start();
$TitrePage="Les utilisateurs et apprenants";
include_once('../inc/fonctions.inc.php'); 
$conn = Connection();

if (is_null($_SESSION['AccesOK'])) {
	header('Location: ../login.php');
}
//if ($_SESSION['RoleNvcCode']="APPR")  {header('Location: login.php');	}
 
if (isset($_GET['ProjIntID'])) { 
	$_SESSION['ProjIntID'] = $_GET['ProjIntID'];
}

if (isset($_GET['SectionIDuSER'])) { 
	$_SESSION['SectionIDuSER'] = $_GET['SectionIDuSER'];
} else {
	$_SESSION['SectionIDuSER'] = 0;
	if ($_SESSION['RoleNvcCode'] == 'GEST-S') {
		$_SESSION['SectionIDuSER'] = $_SESSION['SectionID'];
	};
}

if (isset($_GET['SectNvcLibelle'])) { 
	$_SESSION['SectNvcLibelle'] = $_GET['SectNvcLibelle'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//FR" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
	<title>Edu-MLMS - <?= $_SESSION['ProjNvcLibelle'] ?> - <?= $TitrePage ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="../styles/base.css" rel="stylesheet" type="text/css" />
	<link href="../styles/global.css" rel="stylesheet" type="text/css" />
	<link href="../Projets/<?= $_SESSION['ProjIntID']; ?>/global.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript">
	function MM_jumpMenu(targ,selObj,restore){ //v3.0
	  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	  if (restore) selObj.selectedIndex=0;
	}
	</script>
</HEAD>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'].'/inc/header-light.php'); ?>
<div id="global">
	<div id="conteneur-body">

		<!-- SI MENU LATERAL... -->
		<!--<div id="contenu-avec-menu-lateral">-->
		<!-- contenu ici -->
		<!--</div> -->
		
		<!-- SI PAS DE MENU LATERAL... -->
		<div id="contenu">
			<div class="titre-page">Les Utilisateurs et Apprenants</div>
			<?php if ($_SESSION['RoleNvcCode']=="ADM" ) {
				
			$user = $_SESSION['UtilIntID'];
		
			// echo '<BR/>Login = '.$user;
			$sql = "SELECT * FROM View_Projets where ProjActif = 1 ORDER BY ProjNvcLibelle";
			$stmt = BdListeSELECT($sql,$conn);
			$row_count = mysqli_num_rows( $stmt );
			?>
				<div class="encart-form">
				  	<form name="form" id="form">
				    	Projet :
				      	<select name="jumpMenu" id="jumpMenu" class="select1" onChange="MM_jumpMenu('parent',this,0)">
				     		<?php
							while ($data = mysqli_fetch_array($stmt))
							{
								?> 
				      		<option  <?php if ($data['ProjIntID'] == $_SESSION['ProjIntID']) {?> selected <?php } ?> value="?ProjIntID=<?= $data['ProjIntID']; ?>"><?= $data['ProjNvcLibelle']; ?> </option>
							<?php
					 		} 
					 		?>   
				   
				    	</select>
				  	</form>
			<?php 
			} 
 		$conn = Connection();
		if ($_SESSION['RoleNvcCode']=="ADM" OR $_SESSION['RoleNvcCode']=="GEST" ) {
          	$sql1 = "SELECT * FROM View_Sections WHERE ProjIntID = ".$_SESSION['ProjIntID']." ORDER BY SectNvcLibelle";
		} else {
			$sql1 = "SELECT * FROM View_Sections WHERE ProjIntID = ".$_SESSION['ProjIntID']." And SectionID = ".$_SESSION['SectionID']." ORDER BY SectNvcLibelle";
		}
		
		$stmt1 = BdListeSELECT($sql1,$conn);
	 	?>
     

	 <div style="margin-top:20px">
 
  	<form name="form" id="form">
    Section :
      <select name="jumpMenu" id="jumpMenu" class="select1" onChange="MM_jumpMenu('parent',this,0)">
      
    <?php  if ($_SESSION['RoleNvcCode'] == "ADM" OR $_SESSION['RoleNvcCode'] == "GEST" ) { ?>
           <option value="?SectionIDuSER=0&SectNvcLibelle=TOUTES LES SECTIONS">Toutes</option> 
	 <?php	}  
		while ($data1 = mysqli_fetch_array($stmt1))
		{
		?> 
    
      <option  <?php if ($data1['SectionID'] == $_SESSION['SectionIDuSER']) {?> selected <?php } ?> value="?SectionIDuSER=<?= $data1['SectionID']; ?>&SectNvcLibelle=<?= $data1['SectNvcLibelle']; ?>"><?= $data1['SectNvcLibelle']; ?> </option>
     
	<?php } ?>   
          
    </select> 
  </form>
  </div>  
</div>
<div class="ligne1">
	<div class="tuile-admin">   
		<a href="users/usersStatsList.php" ><i class="fa fa-users fa-3x" aria-hidden="true"></i> <span>Gestion des utilisateurs / inscriptions</span></a>
		<a href="users/createUser.php" ><i class="fa fa-user-plus fa-3x" aria-hidden="true"></i> <span>Ajouter un utilisateur / apprenant</span></a>
	</div>
</div>
<?php include('../inc/footer.php'); ?>
</body>
</HTML>