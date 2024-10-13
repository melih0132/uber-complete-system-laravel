using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace UBER
{
    public class Client : Utilisateur
    {
        private Entreprise? entreprise;

        public Client(string nom, string prenom, DateTime dateDeNaissance, Genre genre, Adresse adresse, string email, Entreprise? entreprise = null) : base(nom, prenom, dateDeNaissance, genre, adresse, email)
        {
            this.Entreprise = entreprise;
        }

        public Entreprise? Entreprise
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