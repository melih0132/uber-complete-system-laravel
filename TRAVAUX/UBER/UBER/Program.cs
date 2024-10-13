namespace UBER
{
    public class Program
    {
        static void Main(string[] args)
        {
            CodePostal cp73000 = new CodePostal(73000);
            List<Ville> villes73 = new List<Ville>
            {
                new Ville("CHAMBERY", cp73000),
                new Ville("LAMOTTE", cp73000)
            };

            CodePostal cp74000 = new CodePostal(74000);
            List<Ville> villes74 = new List<Ville>
            {
                new Ville("ANNECY", cp74000),
                new Ville("PRINGY", cp74000)
            };

            CodePostal cp01000 = new CodePostal(01000);
            List<Ville> villes01 = new List<Ville>
            {
                new Ville("BELLEY", cp01000),
                new Ville("VIRIEU", cp01000)
            };

            Adresse adresseUSMB = new Adresse("27 RUE MARCOZ", villes73, cp73000);
            Adresse adresseptitfaimantalya = new Adresse("27 RUE BERLIOZ", villes01, cp01000);
            Adresse adressecarrefour = new Adresse("27 RUE CARREFOUR", villes74, cp74000);

            Adresse adresseChauffeur = new Adresse("2 RUE JAQUELINE AURIOL", villes74, cp73000);
            Adresse adresseClient = new Adresse("242 AVECNUE DE CHATEAU LARRON", villes01, cp01000);

            Entreprise usmb = new Entreprise(19730858800015, "UNIVERSITE SAVOIE MONT BLANC", adresseUSMB, 3000, SecteurActivite.Etudes);
            Entreprise ptitfaimAntalya = new Entreprise(19730858800016, "P'TIT FAIM ANTALYA", adresseptitfaimantalya, 4, SecteurActivite.Restaurant);
            Entreprise carrefour = new Entreprise(19730858800018, "CARREFOUR", adressecarrefour, 100, SecteurActivite.Epicerie);

            Vehicule bmw = new Vehicule("BMW", "320d", "BY-609-QG", 6, false, true, true, true, false);
            Vehicule velo = new Vehicule("jump", "200", CategorieVehicule.Velo);

            List<double> notes = new List<double>();
            notes.Add(4.3);
            notes.Add(4.8);
            notes.Add(4.6);

            Chauffeur chauffeur = new Chauffeur("CETINKAYA", "Melih", new DateTime(2005, 7, 30), Genre.Homme, adresseChauffeur, "melih.cetinkaya.32@gmail.com", usmb, bmw, true, "12345678901234567890112", notes);

            Client client = new Client("CETINKAYA", "Bilal", new DateTime(1977, 8, 29), Genre.Homme, adresseClient, "bilal.cetinkaya@gmail.com");

            DateTime priseEnCharge = DateTime.Now;
            Course course = new Course(client, chauffeur, adresseUSMB, adresseUSMB, priseEnCharge, 6);




            Produit pate = new Produit("pâtes", 1.68, carrefour);
            Repas kebab = new Repas("kebab", 8, ptitfaimAntalya);

            Panier panier = new Panier();
            panier.AjouterRepas(kebab);

            Commande commande = new Commande(client, chauffeur, adresseClient, TypeLivraison.Livraison, panier);
        }
    }
}