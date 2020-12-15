<?php
session_start();
$nav = true;
$TitrePage="Les utilisateurs et apprenants";
include_once('../../inc/fonctions.inc.php');

if (is_null($_SESSION['AccesOK'])) {
    header('Location: login.php');
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//FR" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">

<HEAD>
    <title>Edu-MLMS - <?= $_SESSION['ProjNvcLibelle'] ?> - <?= $TitrePage ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="../../styles/base.css" rel="stylesheet" type="text/css" />
    <link href="../../styles/global.css" rel="stylesheet" type="text/css" />
    <link href="../../Projets/<?= $_SESSION['ProjIntID']; ?>/global.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
        //<![CDATA[
        function verifjs()
        {
            return window.confirm('Confirmez !');
        }
        //]]>
    </script>

    <style type="text/css">
        #conteneur-body #contenu .titre-liste-modules #t2 {
            border-top-width: 1px;
            border-top-style: solid;
            border-top-color: #666;
            margin-bottom: 10px;
        }
    </style>
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
            <?php include('../../inc/logo-entreprise.php');
            
            if (isset($_GET['UtilIntID'])) {
                $UtilIntID = $_GET['UtilIntID'];
            }
            if (isset($_GET['UtilClef'])) {
                $UtilClef = $_GET['UtilClef'];
            }

            $conn = Connection();

            if (isset($_GET['UtilClef'])) {
                $sql0 = "SELECT * FROM Utilisateurs WHERE UtilClef = '".$UtilClef."' ";
                $stmt0 = mysqli_query($conn, $sql0);
                $result0 = mysqli_fetch_object($stmt0);
                $UtilIntID = $result0->UtilIntID;
            }

            $sql1 = "SELECT * FROM View_Projets ORDER BY ProjNvcLibelle ";
            $sql2 = "SELECT * FROM View_Cours WHERE ProjIntID = ".$_SESSION['ProjIntID']." AND actif = 1 ORDER BY ProjIntID, CourIntID ";
            if ($_SESSION['RoleNvcCode'] == "ADM") {
                $sql2 = "SELECT * FROM View_Cours WHERE actif = 1 ORDER BY ProjIntID, CourIntID ";
            }
            $sql3 = "SELECT * FROM View_Inscriptions WHERE UtilIntID = ".$UtilIntID." AND actif = 1 ";
            $sql4 = "SELECT * FROM View_Utilisateurs WHERE UtilIntID = ".$UtilIntID." ";

            $stmt1 = mysqli_query($conn, $sql1);
            $stmt2 = mysqli_query($conn, $sql2);
            $stmt3 = mysqli_query($conn, $sql3);
            $stmt4 = mysqli_query($conn, $sql4);

            $result4 = mysqli_fetch_object($stmt4);
            $UtilNvcNom = $result4->UtilNvcNom;
            $UtilNvcPrenom = $result4->UtilNvcPrenom;
            $ProjIntID = $result4->ProjIntID;
			$_SESSION['result4UtilIntID'] = $result4->UtilIntID;
			$_SESSION['result4UtilNvcMail'] = $result4->UtilNvcMail;
			$_SESSION['result4UtilNvcPrenom'] = $result4->UtilNvcPrenom;
			$_SESSION['result4UtilNvcNom'] = $result4->UtilNvcNom;
			
            ?>
            
            <div style="text-align:center;"><a class="btn" href="../users/usersStatsList.php">Retour</a></div>
            <br>
            <div class="titre-page">Gestion des xInscriptions</div>
            <div class="titre-liste-modules"></div>
            <b><?= $UtilNvcNom  ?> <?= $UtilNvcPrenom  ?></b> - Code ID : <?= $UtilIntID  ?>
            <form action="newRegistrationProcessing.php" method="post">
                <input name="UtilIntID" type="hidden" id="UtilIntID" value="<?= $UtilIntID ?>" size="10">

            <table width="60%" border="0" align="center" cellpadding="0" cellspacing="2">
            <tr>
            <td width="24%" align="left">projet</td>
            <td width="71%" align="left">	
				  	<select name="jumpMenu" id="jumpMenu" class="input100" onChange="MM_jumpMenu('parent',this,0)">
				 		<?php
						while ($data = mysqli_fetch_array($stmt1)) { ?>
				  			<option  <?php if ($data['ProjIntID'] == $_SESSION['ProjIntID']) {?> selected <?php } ?> value="?ProjIntID=<?= $data['ProjIntID']; ?>"><?= $data['ProjNvcLibelle']; ?>
				  			</option>
						<?php
						} 
				 		?>   
					</select>
                    <?php

                    ?>
            </tr>
                    <tr>
                        <td width="24%" align="left">Cours</td>
                        <td width="71%" align="left">	
						<select name="CourIntID" id="CourIntID" class="input100">
                            <option value=" " selected > </option>
                            <?php
                            while ($data2 = mysqli_fetch_array($stmt2))
                            {
                            ?>
                                <option value=" <?= $data2['CourIntID']; ?>">
                                <?= $data2['ProjNvcLibelle']; ?>
                                .                
                                <?= $data2['CourIntID']; ?>
		                        -
		                        <?= $data2['CourNvcLibelle']; ?>
                                </option>
                                <?php
                            }
                            ?>
                            </select>
                        </td>
                        <td width="5%">*</td>
                    </tr>
                    
                    <?php 
						$jour = 31;
						$mois = 12;
						$annee = date('Y');
						?>

                    <tr>
                        <td align="left">Date début</td>
                        <td align="left"><input name="InscDatDebutvalidite" type="date" id="InscDatDebutvalidite" size="10" value="<?= date("Y-m-d") ?>" class="input100"></td>
                        <td>*</td>
                    </tr>
                    <tr>
                        <td align="left">Date fin</td>
                        <td align="left"><input name="InscDatFinValidite" type="date" id="InscDatFinValidite" value="<?= $annee.'-'.$mois.'-'.$jour; ?>" size="10" class="input100"></td>
                        <td>*</td>
                    </tr>
                    <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left"><input type="submit" name="button2" id="button2" class="btn" value="Inscrire au cours"></td>
                      <td>&nbsp;</td>
                    </tr>
                </table>
            </form>
			
            <div class="titre-liste-modules">
                <div id="t2">inscriptions actives</div>
            </div>
            <div>
                <table width="95%" border="0" align="center" cellpadding="2" cellspacing="0">
                    <tr>
                        <td align="left"><strong>Cours</strong></td>
                        <td><strong>id Cours</strong></td>
                        <td><strong>Date début</strong></td>
                        <td><strong>Date Fin</strong></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <?php
                    while ($data3 = mysqli_fetch_array($stmt3))
                    {
                        $timeDebut = strtotime($data3['InscDatDebutvalidite']);
                        $timeFin = strtotime($data3['InscDatFinValidite']);
                        $DateDebut = date('d/m/Y', $timeDebut);
                        $DateFin = date('d/m/Y', $timeFin);
                        ?>
                        <tr>
                            <td align="left"> <?= $data3['CourNvcLibelle']; ?> </td>
                            <td> <?= $data3['CourIntID']; ?> </td>
                            <td><?= $DateDebut; ?></td>


                            <td >
                                <?= $DateFin; ?>
                            </td>
                            <td>
                                <a href="modifyRegistration.php?InscIntID=<?= $data3['InscIntID']; ?>&UtilIntID=<?= $data3['UtilIntID']; ?>">
                                    <img src="/img/pictos/time.png" width="24" height="23">
                            </td>
                            <td>
                                <a onclick="return verifjs();" href="deleteRegistration.php?InscIntID=<?= $data3['InscIntID']; ?>&UtilIntID=<?= $data3['UtilIntID']; ?>">
                                    <img src="/img/pictos/picto-del.png" width="16" />
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <div class="btn-std-2"> <a href="../users/userNotification.php?UtilIntID=<?= $UtilIntID; ?>&ProjIntID=<?= $ProjIntID; ?>">Envoyer la convocation email</a></div>
            </div>
        </div>
        <?php include('../../inc/footer.php'); ?>
    </div>
</body>
</HTML>