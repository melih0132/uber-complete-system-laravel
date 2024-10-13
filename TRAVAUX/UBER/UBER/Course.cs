using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace UBER
{
    public class Course
    {
        private Client client;
        private Chauffeur chauffeur;
        private CategorieVehicule typePrestation;

        private double prix;

        private Adresse localisationDepart;
        private Adresse localisationArrive;
        private DateTime priseEnCharge;
        private int nbPersonne;

        public Course(Client client, Chauffeur chauffeur, Adresse localisationDepart, Adresse localisationArrive, DateTime priseEnCharge, int nbPersonne)
        {
            this.Client = client;
            this.Chauffeur = chauffeur;
            this.LocalisationDepart = localisationDepart;
            this.LocalisationArrive = localisationArrive;
            this.PriseEnCharge = priseEnCharge;
            this.NbPersonne = nbPersonne;
            this.TypePrestation = Chauffeur.Vehicule.CategorieVehicule;
            this.Prix = CalculerPrixCourse();
        }

        private double CalculerPrixCourse()
        {
            return (double)this.Prix;
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

        public Adresse LocalisationDepart
        {
            get
            {
                return this.localisationDepart;
            }

            set
            {
                this.localisationDepart = value;
            }
        }

        public Adresse LocalisationArrive
        {
            get
            {
                return this.localisationArrive;
            }

            set
            {
                this.localisationArrive = value;
            }
        }

        public DateTime PriseEnCharge
        {
            get
            {
                return this.priseEnCharge;
            }

            set
            {
                this.priseEnCharge = value;
            }
        }

        public int NbPersonne
        {
            get
            {
                return this.nbPersonne;
            }

            set
            {
                this.nbPersonne = value;
            }
        }

        public CategorieVehicule TypePrestation
        {
            get
            {
                return this.typePrestation;
            }

            set
            {
                this.typePrestation = value;
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
    }
}