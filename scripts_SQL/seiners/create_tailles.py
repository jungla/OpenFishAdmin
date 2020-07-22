f = open('cTable_peche_thon_rejete_taille.sql','w')

f.write('''
   DROP TABLE IF EXISTS peche_thon.thon_rejete_taille;
   CREATE TABLE peche_thon.thon_rejete_taille(
   id uuid DEFAULT uuid_generate_v4 (),
   datetime timestamp DEFAULT now(),
   username varchar(100),
   maree varchar(100),
   n_calee integer,
   n_route integer,
   l_route integer,
   id_route uuid,
   '''+'\n'
)


taille = range(9,101)+[110,111,112,135,138,139,140,144,145,146,147,148,149,150,151,154,155,156,157,158,159,160,170]

for i in taille:
 f.write("   c"+str(i).zfill(3)+" integer,"+'\n')

f.write('''   PRIMARY KEY(id)
);''')


