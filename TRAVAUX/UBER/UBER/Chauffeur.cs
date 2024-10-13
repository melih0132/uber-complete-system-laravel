using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace UBER
{
    public class Chauffeur : Utilisateur
    {
        private Entreprise entreprise;
        private Vehicule vehicule;
        private bool disponible;
        private string rib;
        private List<double> notes;

        public Chauffeur(string nom, string prenom, DateTime dateDeNaissance, Genre genre, Adresse adresse, string email, Entreprise entreprise, Vehicule vehicule, bool disponible, string rib, List<double> notes) : base(nom, prenom, dateDeNaissance, genre, adresse, email)
        {
            this.Entreprise = entreprise;
            this.Vehicule = vehicule;
            this.Disponible = disponible;
            this.Rib = rib;
            this.Notes = notes ?? new List<double>();
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

        public Vehicule Vehicule
        {
            get
            {
                return this.vehicule;
            }

            set
            {
                this.vehicule = value;
            }
        }

        public bool Disponible
        {
            get
            {
                return this.disponible;
            }

            set
            {
                this.disponible = value;
            }
        }

        public string Rib
        {
            get
            {
                return this.rib;
            }

            set
            {
                this.rib = value;
            }
        }

        public List<double> Notes
        {
            get
            {
                return this.notes;
            }

            set
            {
                this.notes = value;
            }
        }
    }

    public enum CategorieVehicule
    {
        Velo,
        Green,
        XL,
        Pet,
        Confort,
        Berline,
        X
    }

    public class Vehicule
    {
        private string marque;
        private string modele;
        private string immatriculation;
        private CategorieVehicule categorieVehicule;

        private int capacite;
        private bool estElectrique;
        private bool accepteAnimaux;
        private bool estConfortable;
        private bool estRecent;
        private bool estLuxueux;

        public Vehicule(string marque, string modele, string immatriculation, int capacite, bool estElectrique, bool accepteAnimaux, bool estConfortable, bool estRecent, bool estLuxueux)
        {
            this.Marque = marque;
            this.Modele = modele;
            this.Immatriculation = immatriculation;
            this.Capacite = capacite;
            this.EstElectrique = estElectrique;
            this.AccepteAnimaux = accepteAnimaux;
            this.EstConfortable = estConfortable;
            this.EstRecent = estRecent;
            this.EstLuxueux = estLuxueux;
            this.CategorieVehicule = AttributionCategorie();
        }

        public Vehicule(string marque, string modele, CategorieVehicule categorieVehicule)
        {
            this.Marque = marque;
            this.Modele = modele;
            this.CategorieVehicule = categorieVehicule;
        }

        public string Marque
        {
            get
            {
                return this.marque;
            }

            set
            {
                this.marque = value;
            }
        }

        public string Modele
        {
            get
            {
                return this.modele;
            }

            set
            {
                this.modele = value;
            }
        }

        public string Immatriculation
        {
            get
            {
                return this.immatriculation;
            }

            set
            {
                this.immatriculation = value;
            }
        }

        public CategorieVehicule CategorieVehicule
        {
            get
            {
                return this.categorieVehicule;
            }

            set
            {
                this.categorieVehicule = value;
            }
        }

        public int Capacite
        {
            get
            {
                return this.capacite;
            }

            set
            {
                this.capacite = value;
            }
        }

        public bool EstElectrique
        {
            get
            {
                return this.estElectrique;
            }

            set
            {
                this.estElectrique = value;
            }
        }

        public bool AccepteAnimaux
        {
            get
            {
                return this.accepteAnimaux;
            }

            set
            {
                this.accepteAnimaux = value;
            }
        }

        public bool EstConfortable
        {
            get
            {
                return this.estConfortable;
            }

            set
            {
                this.estConfortable = value;
            }
        }

        public bool EstRecent
        {
            get
            {
                return this.estRecent;
            }

            set
            {
                this.estRecent = value;
            }
        }

        public bool EstLuxueux
        {
            get
            {
                return this.estLuxueux;
            }

            set
            {
                this.estLuxueux = value;
            }
        }

        public CategorieVehicule AttributionCategorie()
        {
            if (EstElectrique)
            {
                return CategorieVehicule.Green;
            }
            else if (Capacite >= 6)
            {
                return CategorieVehicule.XL;
            }
            else if (AccepteAnimaux)
            {
                return CategorieVehicule.Pet;
            }
            else if (EstConfortable && EstRecent)
            {
                return CategorieVehicule.Confort;
            }
            else if (EstLuxueux)
            {
                return CategorieVehicule.Berline;
            }
            else
            {
                return CategorieVehicule.X;
            }
        }
    }
}