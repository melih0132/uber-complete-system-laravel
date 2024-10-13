using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace UBER
{
    public enum TypeLivraison
    {
        Livraison, 
        Emporter
    }

    public class Commande
    {
        private Client client;
        private Chauffeur chauffeur;

        private Adresse adresse;
        private TypeLivraison typeLivraison;
        private Panier panier;

        public Commande(Client client, Chauffeur chauffeur, Adresse adresse, TypeLivraison typeLivraison, Panier panier)
        {
            this.Client = client;
            this.Chauffeur = chauffeur;
            this.Adresse = adresse;
            this.TypeLivraison = typeLivraison;
            this.Panier = panier;
        }

        public Client Client
        {
            get
            {
                return this.client;
            }

            set
            {
                this.client = value;
            }
        }

        public Chauffeur Chauffeur
        {
            get
            {
                return this.chauffeur;
            }

            set
            {
                this.chauffeur = value;
            }
        }

        public Adresse Adresse
        {
            get
            {
                return this.adresse;
            }

            set
            {
                this.adresse = value;
            }
        }

        public TypeLivraison TypeLivraison
        {
            get
            {
                return this.typeLivraison;
            }

            set
            {
                this.typeLivraison = value;
            }
        }

        public Panier Panier
        {
            get
            {
                return this.panier;
            }

            set
            {
                this.panier = value;
            }
        }
    }

    public class Panier
    {
        private List<Produit> produits;
        private List<Repas> repas;

        public Panier()
        {
            this.produits = new List<Produit>();
            this.repas = new List<Repas>();
        }

        public void AjouterProduit(Produit produit)
        {
            produits.Add(produit);
        }

        public void AjouterRepas(Repas repas)
        {
            this.repas.Add(repas);
        }

        public double CalculerPrixTotal()
        {
            double total = 0;

            foreach (var produit in produits)
            {
                total += produit.Prix;
            }

            foreach (var repas in repas)
            {
                total += repas.Prix;
            }

            return total;
        }
    }

    public class Repas
    {
        private string nom;
        private double prix;

        private Entreprise entreprise;

        public Repas(string nom, double prix, Entreprise entreprise)
        {
            this.Nom = nom;
            this.Prix = prix;
            this.Entreprise = entreprise;
        }

        public string Nom
        {
            get
            {
                return this.nom;
            }

            set
            {
                this.nom = value;
            }
        }

        public double Prix
        {
            get
            {
                return this.prix;
            }

            set
            {
                this.prix = value;
            }
        }

        public Entreprise Entreprise
        {
            get
            {
                return this.entreprise;
            }

            set
            {
                this.entreprise = value;
            }
        }
    }

    public class Produit
    {
        private string nom;
        private double prix;

        private Entreprise entreprise;

        public Produit(string nom, double prix, Entreprise entreprise)
        {
            this.Nom = nom;
            this.Prix = prix;
            this.Entreprise = entreprise;
        }

        public string Nom
        {
            get
            {
                return this.nom;
            }

            set
            {
                this.nom = value;
            }
        }

        public double Prix
        {
            get
            {
                return this.prix;
            }

            set
            {
                this.prix = value;
            }
        }

        public Entreprise Entreprise
        {
            get
            {
                return this.entreprise;
            }

            set
            {
                this.entreprise = value;
            }
        }
    }
}